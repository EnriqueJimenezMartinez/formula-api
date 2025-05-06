<?php
declare(strict_types=1);

/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link      https://cakephp.org CakePHP(tm) Project
 * @since     3.3.0
 * @license   https://opensource.org/licenses/mit-license.php MIT License
 */
namespace App;

use App\Error\ApiExceptionRenderer;
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
 * Application setup class.
 *
 * This defines the bootstrapping logic and middleware layers you
 * want to use in your application.
 *
 * @extends \Cake\Http\BaseApplication<\App\Application>
 */
class Application extends BaseApplication implements AuthenticationServiceProviderInterface
{
    /**
     * Load all the application configuration and bootstrap logic.
     *
     * @return void
     */
    public function bootstrap(): void
    {
        // Call parent to load bootstrap from files.
        parent::bootstrap();

        if (PHP_SAPI !== 'cli') {
            FactoryLocator::add(
                'Table',
                (new TableLocator())->allowFallbackClass(false),
            );
        }
        $this->addPlugin('Authentication');
    }

    /**
     * Setup the middleware queue your application will use.
     *
     * @param \Cake\Http\MiddlewareQueue $middlewareQueue The middleware queue to setup.
     * @return \Cake\Http\MiddlewareQueue The updated middleware queue.
     */
    public function middleware(MiddlewareQueue $middlewareQueue): MiddlewareQueue
    {
        $middlewareQueue
            // Catch any exceptions in the lower layers,
            // and make an error page/response
            ->add(new ErrorHandlerMiddleware([
                'exceptionRenderer' => ApiExceptionRenderer::class,
            ], $this))

            // Handle plugin/theme assets like CakePHP normally does.
            ->add(new AssetMiddleware([
                'cacheTime' => Configure::read('Asset.cacheTime'),
            ]))

            // Add routing middleware.
            // If you have a large number of routes connected, turning on routes
            // caching in production could improve performance.
            // See https://github.com/CakeDC/cakephp-cached-routing
            ->add(new RoutingMiddleware($this))

            // Parse various types of encoded request bodies so that they are
            // available as array through $request->getData()
            // https://book.cakephp.org/5/en/controllers/middleware.html#body-parser-middleware
            ->add(new BodyParserMiddleware())

            ->add(new AuthenticationMiddleware($this));
            /*->add(function ($request, $handler) {
                if ($request->getParam('prefix') === 'Admin') {
                    $service = $this->getAuthenticationService($request);
                    $authMiddleware = new \Authentication\Middleware\AuthenticationMiddleware($service);
                    return $authMiddleware->process($request, $handler);
                }

                return $handler->handle($request);
            })*/
            // Cross Site Request Forgery (CSRF) Protection Middleware
            // https://book.cakephp.org/5/en/security/csrf.html#cross-site-request-forgery-csrf-middleware
            $csrf = new CsrfProtectionMiddleware([
                'httpOnly' => true,
            ]);
            $csrf->skipCheckCallback(function (ServerRequestInterface $request): bool {
                // Si el prefijo es “api”, NO aplicamos CSRF
                    $params = $request->getAttribute('params') ?? [];
                    $params = $request->getAttribute('params') ?? [];
                    $prefix = isset($params['prefix']) ? strtolower((string)$params['prefix']) : '';

                return $prefix === 'api';
            });
            $middlewareQueue->add($csrf);

        return $middlewareQueue;
    }

    /**
     * Register application container services.
     *
     * @param \Cake\Core\ContainerInterface $container The Container to update.
     * @return void
     * @link https://book.cakephp.org/5/en/development/dependency-injection.html#dependency-injection
     */
    public function services(ContainerInterface $container): void
    {
    }

    public function getAuthenticationService(ServerRequestInterface $request): AuthenticationServiceInterface
    {

        // Load the authenticators, you want session first
        $prefix = $request->getAttribute('params')['prefix'] ?? null;
        if ($prefix === 'Api') {
            $authenticationService = new AuthenticationService([
                'unauthenticatedRedirect' => null,
                'queryParam'              => null,
                'unauthenticatedHandler'  => [
                    'className' => 'Authentication.HttpUnauthorizedHandler',
                    'config'    => [
                        'wwwAuthenticate' => 'Bearer realm="api"',
                    ],
                ],
            ]);
            // --- Scope API: solo JWT ---
            $authenticationService->loadIdentifier('Authentication.JwtSubject');
            $authenticationService->loadAuthenticator('Authentication.Jwt', [
                // Tu secret salt en config/app.php > Security.salt
                'secretKey'    => Configure::read('JWT_SECRET'),
                'header'       => 'Authorization',
                'tokenPrefix'  => 'Bearer',
                'algorithm'    => 'HS256',
                // opcional: permitir token en query (?token=…)
                //'queryParam'   => 'token',
            ]);
            $fields = [
                AbstractIdentifier::CREDENTIAL_USERNAME => 'email',
                AbstractIdentifier::CREDENTIAL_PASSWORD => 'password',
            ];
            $authenticationService->loadIdentifier('Authentication.Password', compact('fields'));
            $authenticationService->loadAuthenticator('Authentication.Form', [
                'fields'   => $fields,
                'loginUrl' => '/api/users/login',
            ]);
        } else {
            $authenticationService = new AuthenticationService([
                'unauthenticatedRedirect' => Router::url([
                    'controller' =>  'Users',
                    'action' => 'login',
                    'prefix' => 'Admin',
                ]),
                'queryParam' => 'redirect',
            ]);

            // Load identifiers, ensure we check email and password fields
            $authenticationService->loadIdentifier('Authentication.Password', [
                'fields' => [
                    'username' => 'email',
                    'password' => 'password',
                ],
            ]);
            $authenticationService->loadAuthenticator('Authentication.Session');
            // Configure form data check to pick email and password
            $authenticationService->loadAuthenticator('Authentication.Form', [
                'fields' => [
                    'username' => 'email',
                    'password' => 'password',
                ],
                'loginUrl' => Router::url([
                    'controller' =>  'Users',
                    'action' => 'login',
                    'prefix' => 'Admin',
                ]),
            ]);
        }

        return $authenticationService;
    }
}
