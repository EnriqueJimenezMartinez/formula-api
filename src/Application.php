<?php
declare(strict_types=1);

namespace App;

use App\Error\ApiExceptionRenderer;
use App\Middleware\CorsMiddleware;
use Authentication\AuthenticationService;
use Authentication\AuthenticationServiceInterface;
use Authentication\AuthenticationServiceProviderInterface;
use Authentication\Identifier\AbstractIdentifier;
use Authentication\Middleware\AuthenticationMiddleware;
use Cake\Core\Configure;
use Cake\Core\ContainerInterface;
use Cake\Datasource\FactoryLocator;
use Cake\Error\Middleware\ErrorHandlerMiddleware;
use Cake\Http\BaseApplication;
use Cake\Http\Middleware\BodyParserMiddleware;
use Cake\Http\Middleware\CsrfProtectionMiddleware;
use Cake\Http\MiddlewareQueue;
use Cake\ORM\Locator\TableLocator;
use Cake\Routing\Middleware\AssetMiddleware;
use Cake\Routing\Middleware\RoutingMiddleware;
use Cake\Routing\Router;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Clase principal de configuración de la aplicación.
 *
 * Define la lógica de arranque y la cola de middlewares que se utilizan.
 */
class Application extends BaseApplication implements AuthenticationServiceProviderInterface
{
    /**
     * Carga la configuración y el arranque de la aplicación.
     *
     * @return void
     */
    public function bootstrap(): void
    {
        // Llama al método padre para cargar bootstrap desde los archivos de configuración.
        parent::bootstrap();

        if (PHP_SAPI !== 'cli') {
            FactoryLocator::add(
                'Table',
                (new TableLocator())->allowFallbackClass(false),
            );
        }

        // Añade el plugin de autenticación de usuarios
        $this->addPlugin('Authentication');
    }

    /**
     * Configura los middlewares que utilizará la aplicación.
     *
     * @param \Cake\Http\MiddlewareQueue $middlewareQueue Cola de middlewares a configurar.
     * @return \Cake\Http\MiddlewareQueue Cola de middlewares actualizada.
     */
    public function middleware(MiddlewareQueue $middlewareQueue): MiddlewareQueue
    {
        $middlewareQueue
            // Captura cualquier excepción que se produzca en capas inferiores
            // y genera una respuesta de error adecuada
            ->add(new ErrorHandlerMiddleware([
                'exceptionRenderer' => ApiExceptionRenderer::class,
            ], $this))

            // Gestiona los archivos estáticos de plugins y temas
            ->add(new AssetMiddleware([
                'cacheTime' => Configure::read('Asset.cacheTime'),
            ]))

            // Añade el middleware de rutas, necesario para procesar URLs
            ->add(new RoutingMiddleware($this))

            // Convierte automáticamente el cuerpo de las peticiones a un array accesible
            ->add(new BodyParserMiddleware())

            // Middleware de autenticación (identifica al usuario en cada petición)
            ->add(new AuthenticationMiddleware($this));

        // Middleware de protección contra ataques CSRF
        $csrf = new CsrfProtectionMiddleware([
            'httpOnly' => true,
        ]);

        // Se desactiva la protección CSRF si el prefijo de la ruta es "api"
        $csrf->skipCheckCallback(function (ServerRequestInterface $request): bool {
            $params = $request->getAttribute('params') ?? [];
            $prefix = isset($params['prefix']) ? strtolower((string)$params['prefix']) : '';

            return $prefix === 'api';
        });

        $middlewareQueue->add($csrf)

        // Middleware personalizado para permitir CORS
        ->add(new CorsMiddleware());
        
        return $middlewareQueue;
    }

    /**
     * Registro de servicios personalizados en el contenedor de dependencias (opcional).
     *
     * @param \Cake\Core\ContainerInterface $container Contenedor a modificar.
     * @return void
     */
    public function services(ContainerInterface $container): void
    {
        // Actualmente no se registran servicios personalizados.
    }

    /**
     * Configura y devuelve el servicio de autenticación según el tipo de solicitud.
     *
     * Distingue entre solicitudes API (prefijo "Api") y del panel de administración (prefijo "Admin"):
     *
     * - Para API:
     *   - Se usa autenticación JWT (token en cabecera Authorization).
     *   - No se permiten redirecciones.
     *   - Se lanza error 401 si no hay token o es inválido.
     *
     * - Para Admin:
     *   - Se usa sesión + formulario de login.
     *   - Permite redirigir al formulario de login si no hay sesión activa.
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @return \Authentication\AuthenticationServiceInterface
     */
    public function getAuthenticationService(ServerRequestInterface $request): AuthenticationServiceInterface
    {
        $prefix = $request->getAttribute('params')['prefix'] ?? null;

        // Configuración para autenticación API (JWT)
        if ($prefix === 'Api') {
            $authenticationService = new AuthenticationService([
                'unauthenticatedRedirect' => null,
                'queryParam' => null,
                'unauthenticatedHandler' => [
                    'className' => 'Authentication.HttpUnauthorizedHandler',
                    'config' => [
                        'wwwAuthenticate' => 'Bearer realm="api"',
                    ],
                ],
            ]);

            // Identificador JWT
            $authenticationService->loadIdentifier('Authentication.JwtSubject');

            // Autenticador JWT usando el token en la cabecera "Authorization"
            $authenticationService->loadAuthenticator('Authentication.Jwt', [
                'secretKey' => Configure::read('JWT_SECRET'),
                'header' => 'Authorization',
                'tokenPrefix' => 'Bearer',
                'algorithm' => 'HS256',
            ]);

            // También se permite login por email y password en /api/users/login
            $fields = [
                AbstractIdentifier::CREDENTIAL_USERNAME => 'email',
                AbstractIdentifier::CREDENTIAL_PASSWORD => 'password',
            ];
            $authenticationService->loadIdentifier('Authentication.Password', compact('fields'));
            $authenticationService->loadAuthenticator('Authentication.Form', [
                'fields' => $fields,
                'loginUrl' => '/api/users/login',
            ]);
        } else {
            // Configuración para panel de administración (login por sesión y formulario)
            $authenticationService = new AuthenticationService([
                'unauthenticatedRedirect' => Router::url([
                    'controller' => 'Users',
                    'action' => 'login',
                    'prefix' => 'Admin',
                    'plugin' => false,
                ]),
                'queryParam' => 'redirect',
            ]);

            // Identificación por email y contraseña
            $authenticationService->loadIdentifier('Authentication.Password', [
                'fields' => [
                    'username' => 'email',
                    'password' => 'password',
                ],
            ]);

            // Autenticación por sesión activa
            $authenticationService->loadAuthenticator('Authentication.Session');

            // Autenticación por formulario (login manual)
            $authenticationService->loadAuthenticator('Authentication.Form', [
                'fields' => [
                    'username' => 'email',
                    'password' => 'password',
                ],
                'loginUrl' => Router::url([
                    'controller' => 'Users',
                    'action' => 'login',
                    'prefix' => 'Admin',
                    'plugin' => false,
                ]),
            ]);
        }

        return $authenticationService;
    }
}
