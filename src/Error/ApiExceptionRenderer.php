<?php
declare(strict_types=1);

namespace App\Error;

use Authentication\Authenticator\UnauthenticatedException;
use Cake\Error\Renderer\WebExceptionRenderer;
use Cake\Http\Response;

class ApiExceptionRenderer extends WebExceptionRenderer
{
    /**
     * Atrapa cualquier excepción y, si es
     * UnauthenticatedException, devuelve un 401 JSON.
     */
   /* public function render(): Response
    {
        $exception = $this->error;

        if ($exception instanceof UnauthenticatedException) {
            $body = json_encode([
                'status' => 'error',
                'message' => $exception->getMessage() ?: 'Unauthorized',
                'data' => null,
            ]);

            return (new Response())
                ->withType('application/json')
                ->withStatus(401)
                ->withStringBody($body);
        }

        // Para el resto de excepciones, delega al comportamiento por defecto
        return parent::render();
    }*/
    public function render(): Response
    {
        $response = parent::render();

        $allowedOrigins = [
            'http://localhost:5174',
            'https://formula-front.vercel.app',
        ];
        $origin = $this->controller->getRequest()->getHeaderLine('Origin');

        if (in_array($origin, $allowedOrigins, true)) {
            $response = $response
                ->withHeader('Access-Control-Allow-Origin', $origin)
                ->withHeader('Access-Control-Allow-Credentials', 'true');
        }

        $response = $response
            ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, PATCH, DELETE, OPTIONS')
            ->withHeader('Access-Control-Allow-Headers', 'Origin, Content-Type, Accept, Authorization');

        return $response;
    }
}
