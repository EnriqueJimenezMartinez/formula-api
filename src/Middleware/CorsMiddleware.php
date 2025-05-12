<?php
// src/Middleware/CorsMiddleware.php
declare(strict_types=1);

namespace App\Middleware;

use Cake\Http\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class CorsMiddleware implements MiddlewareInterface
{
    /**
     * Este método se encarga de procesar las solicitudes entrantes y aplicar los encabezados CORS necesarios.
     *
     * Primero, maneja las solicitudes de tipo "preflight" (OPTIONS) para que el navegador
     * permita el acceso a recursos desde otros orígenes. Luego, agrega los encabezados
     * necesarios para permitir el acceso a los recursos desde el frontend.
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request La solicitud entrante.
     * @param \Psr\Http\Server\RequestHandlerInterface $handler El manejador de la solicitud.
     * @return \Psr\Http\Message\ResponseInterface La respuesta procesada.
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        // Si la solicitud es de tipo OPTIONS (preflight), devolvemos una respuesta 200 vacía
        // Esto es necesario para permitir que el navegador realice solicitudes cross-origin.
        if (strtoupper($request->getMethod()) === 'OPTIONS') {
            // Respuesta vacía para solicitudes OPTIONS
            $response = new Response();
        } else {
            // Para las demás solicitudes (GET, POST, PUT, etc.), continuamos el procesamiento normal
            $response = $handler->handle($request);
        }

        // Añadimos los encabezados CORS a la respuesta
        return $response
            // Permitir solicitudes desde un dominio específico (por ejemplo, el frontend)
            ->withHeader('Access-Control-Allow-Origin', 'http://localhost:5174') // Dominio de origen permitido
            // Especificamos los métodos HTTP permitidos
            ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, PATCH, DELETE, OPTIONS')
            // Especificamos los encabezados permitidos
            ->withHeader('Access-Control-Allow-Headers', 'Content-Type, Authorization')
            // Permitir el envío de credenciales (como cookies) o no (en este caso, 'false')
            ->withHeader('Access-Control-Allow-Credentials', 'false');
    }
}
