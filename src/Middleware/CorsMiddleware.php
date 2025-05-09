<?php
// src/Middleware/CorsMiddleware.php
declare(strict_types=1);

namespace App\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Server\MiddlewareInterface;
use Cake\Http\Response;

class CorsMiddleware implements MiddlewareInterface
{
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        // Si es preflight OPTIONS, devolvemos un 200 vacío
        if (strtoupper($request->getMethod()) === 'OPTIONS') {
            $response = new Response();
        } else {
            $response = $handler->handle($request);
        }

        // Añadimos los headers CORS
        return $response
            ->withHeader('Access-Control-Allow-Origin', 'http://localhost:5174')
            ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, PATCH, DELETE, OPTIONS')
            ->withHeader('Access-Control-Allow-Headers', 'Content-Type, Authorization')
            ->withHeader('Access-Control-Allow-Credentials', 'false');
        }
}
