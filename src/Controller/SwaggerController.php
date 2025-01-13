<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SwaggerController
{
    #[Route('', name: 'swagger_file', methods: ['GET'])]
    public function swaggerFile(): Response
    {
        $filePath = __DIR__ . '/../../public//swagger/index.html';

        if (!file_exists($filePath)) {
            return new Response('Swagger UI not found', 404);
        }

        return new Response(file_get_contents($filePath), 200, [
            'Content-Type' => 'text/html',
        ]);
    }
}