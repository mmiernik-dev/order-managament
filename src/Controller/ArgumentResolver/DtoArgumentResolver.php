<?php

declare(strict_types=1);

namespace App\Controller\ArgumentResolver;

use App\Dto\DtoInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ArgumentValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class DtoArgumentResolver  implements ArgumentValueResolverInterface
{
    private SerializerInterface $serializer;
    private ValidatorInterface $validator;

    public function __construct(SerializerInterface $serializer, ValidatorInterface $validator)
    {
        $this->serializer = $serializer;
        $this->validator = $validator;
    }

    public function supports(Request $request, ArgumentMetadata $argument): bool
    {
        return class_exists($argument->getType()) && is_subclass_of($argument->getType(), DtoInterface::class);
    }

    public function resolve(Request $request, ArgumentMetadata $argument): \Generator
    {
        $dtoClass = $argument->getType();

        // Get data from request
        $data = json_decode($request->getContent(), true);
        if (!$data) {
            throw new BadRequestHttpException('Invalid JSON payload');
        }

        // Deserialized
        $dto = $this->serializer->deserialize(json_encode($data), $dtoClass, 'json');

        // Validation
        $errors = $this->validator->validate($dto);
        if (count($errors) > 0) {
            $errorMessages = [];
            foreach ($errors as $error) {
                $errorMessages[] = [
                    'field' => $error->getPropertyPath(),
                    'message' => $error->getMessage(),
                ];
            }

            throw new BadRequestHttpException(json_encode($errorMessages));
        }

        // Return DTO
        yield $dto;
    }
}