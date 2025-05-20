<?php
declare(strict_types=1);

namespace App\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class AllowOptionsMiddleware implements MiddlewareInterface
{
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        if (strtoupper($request->getMethod()) === 'OPTIONS') {
            // Puedes ajustar allowed origins según lo necesites
            $origin = $request->getHeaderLine('Origin');
            $allowedOrigins = [
                'http://localhost:5174',
                'https://formula-front.vercel.app',
            ];

            $response = new \Cake\Http\Response();

            if (in_array($origin, $allowedOrigins, true)) {
                $response = $response
                    ->withHeader('Access-Control-Allow-Origin', $origin)
                    ->withHeader('Access-Control-Allow-Credentials', 'true');
            }

            return $response
                ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, PATCH, DELETE, OPTIONS')
                ->withHeader('Access-Control-Allow-Headers', 'Origin, Content-Type, Accept, Authorization')
                ->withStatus(200);
        }

        // Si no es OPTIONS, continúa el flujo normal
        return $handler->handle($request);
    }
}
