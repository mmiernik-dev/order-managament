<?php

declare(strict_types=1);

namespace App\EventListener;

use Doctrine\ORM\EntityNotFoundException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ExceptionListener
{
    public function onKernelException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();

        // Handle with Not found entity exception
        if ($exception instanceof EntityNotFoundException) {
            $response = new JsonResponse([
                'error' => 'Resource not found',
                'details' => $exception->getMessage(),
            ], 404);
            $event->setResponse($response);
        }

        // Another exceptions NotFoundHttpException
        if ($exception instanceof NotFoundHttpException) {
            $response = new JsonResponse([
                'error' => 'Route not found',
                'details' => $exception->getMessage(),
            ], 404);
            $event->setResponse($response);
        }
    }
}