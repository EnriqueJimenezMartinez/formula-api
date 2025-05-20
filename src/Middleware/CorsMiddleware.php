<?php
declare(strict_types=1);

namespace App\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class CorsMiddleware implements MiddlewareInterface
{
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $allowedOrigins = [
            'http://localhost:5174',
            'https://formula-front.vercel.app',
        ];

        $origin = $request->getHeaderLine('Origin');

        // Procesa la petición normalmente, incluso si hay error
        $response = $handler->handle($request);

        // Permite solo los orígenes autorizados
        if (in_array($origin, $allowedOrigins, true)) {
            $response = $response
                ->withHeader('Access-Control-Allow-Origin', $origin)
                ->withHeader('Access-Control-Allow-Credentials', 'true'); // Cambia a 'true' si usas autenticación
        }

        // Añade SIEMPRE los métodos y headers permitidos
        $response = $response
            ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, PATCH, DELETE, OPTIONS')
            ->withHeader('Access-Control-Allow-Headers', 'Origin, Content-Type, Accept, Authorization');

        // Si es OPTIONS, corta la respuesta aquí (opcional, pero más rápido)
        if (strtoupper($request->getMethod()) === 'OPTIONS') {
            return $response->withStatus(200);
        }

        return $response;
    }
}
