<?php
/**
 * Configuración de las rutas.
 *
 * En este archivo, configuras las rutas hacia tus controladores y sus acciones.
 * Las rutas son un mecanismo muy importante que permite conectar
 * diferentes URLs a los controladores elegidos y sus acciones (funciones).
 *
 * Se carga dentro del contexto del método `Application::routes()`
 * que recibe una instancia de `RouteBuilder` `$routes` como argumento del método.
 *
 * CakePHP(tm): Framework de Desarrollo Rápido (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licenciado bajo la Licencia MIT
 * Para obtener información completa de los derechos de autor y licencia,
 * consulta el archivo LICENSE.txt
 * Las redistribuciones de archivos deben retener el aviso de derechos de autor anterior.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link          https://cakephp.org Proyecto CakePHP(tm)
 * @license       https://opensource.org/licenses/mit-license.php Licencia MIT
 */

use Cake\Controller\Controller;
use Cake\Routing\Route\DashedRoute;
use Cake\Routing\RouteBuilder;

/*
 * Este archivo se carga en el contexto de la clase `Application`.
 * Por lo tanto, puedes usar `$this` para hacer referencia a la instancia de la aplicación
 * si es necesario.
 */
return function (RouteBuilder $routes): void {
    /*
     * La clase predeterminada para usar en todas las rutas
     *
     * Las siguientes clases de rutas son proporcionadas con CakePHP y son apropiadas
     * para establecer como la predeterminada:
     *
     * - Route
     * - InflectedRoute
     * - DashedRoute
     *
     * Si no se llama a `Router::defaultRouteClass()`, la clase que se usa es
     * `Route` (`Cake\Routing\Route\Route`)
     *
     * Ten en cuenta que `Route` no realiza ninguna inflexión en las URLs, lo que resultará en
     * URLs de formato inconsistente cuando se usen los marcadores `{plugin}`, `{controller}` y
     * `{action}`.
     */
    $routes->setRouteClass(DashedRoute::class);

    $routes->scope('/', function (RouteBuilder $builder): void {
        /*
         * Aquí, estamos conectando '/' (camino base) a un controlador llamado 'Pages',
         * su acción llamada 'display', y pasamos un parámetro para seleccionar el archivo de vista
         * a usar (en este caso, templates/Pages/home.php)...
         */
        $builder->connect('/', ['controller' => 'Users', 'action' => 'index', 'prefix' => 'Admin']);
    });

    $routes->prefix('Admin', function (RouteBuilder $routes) {
        // Todas las rutas aquí estarán prefijadas con `/admin`, y
        // tendrán el elemento `'prefix' => 'Admin'` que será necesario
        // cuando se generen URLs para estas rutas
        $routes->connect('/', ['controller' => 'Users', 'action' => 'index']);
        $routes->fallbacks(DashedRoute::class);
    });

    $routes->prefix('Api', function (RouteBuilder $routes) {
        $routes->post('/login',['controller'=>'Users', 'action'=>'login']);
        $routes->post(
            '/news/last-date/:date',
            [
                'controller' => 'News',
                'action' => 'lastDate'
            ]
        )
        ->setPass(['date']);

        $routes->connect('/news/:slug', [
            'controller' => 'News',
            'action' => 'view',
        ])
        ->setPass(['slug']);

        $routes->fallbacks(DashedRoute::class);
    });

    /*
     * Si necesitas un conjunto diferente de middleware o ninguno en absoluto,
     * abre un nuevo scope y define las rutas allí.
     *
     * ```
     * $routes->scope('/api', function (RouteBuilder $builder): void {
     *     // Aquí no se usa $builder->applyMiddleware().
     *
     *     // Puedes analizar extensiones especificadas desde las URLs
     *     // $builder->setExtensions(['json', 'xml']);
     *
     *     // Conecta las acciones de la API aquí.
     * });
     * ```
     */
};
