<?php
declare(strict_types=1);

/**
 * CakePHP(tm) : Framework de Desarrollo Rápido (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licenciado bajo La Licencia MIT
 * Para obtener la información completa de los derechos de autor y la licencia, consulta el archivo LICENSE.txt
 * Las distribuciones de archivos deben mantener la nota de copyright mencionada anteriormente.
 *
 * @copyright Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link      https://cakephp.org Proyecto CakePHP(tm)
 * @since     0.2.9
 * @license   https://opensource.org/licenses/mit-license.php Licencia MIT
 */
namespace App\Controller;

use Cake\Core\Configure;
use Cake\Http\Exception\ForbiddenException;
use Cake\Http\Exception\NotFoundException;
use Cake\Http\Response;
use Cake\View\Exception\MissingTemplateException;

/**
 * Controlador de contenido estático
 *
 * Este controlador renderiza vistas desde templates/Pages/
 *
 * @link https://book.cakephp.org/5/en/controllers/pages-controller.html
 */
class PagesController extends AppController
{
    /**
     * Muestra una vista
     *
     * @param string ...$path Segmentos de la ruta.
     * @return \Cake\Http\Response|null
     * @throws \Cake\Http\Exception\ForbiddenException Si se detecta un intento de navegación fuera de la carpeta.
     * @throws \Cake\View\Exception\MissingTemplateException Si no se encuentra el archivo de vista y estamos en modo debug.
     * @throws \Cake\Http\Exception\NotFoundException Si no se encuentra el archivo de vista y no estamos en modo debug.
     * @throws \Cake\View\Exception\MissingTemplateException En modo debug.
     */
    public function display(string ...$path): ?Response
    {
        // Si no se pasa ningún parámetro de ruta, redirige a la página de inicio.
        if (!$path) {
            return $this->redirect('/');
        }

        // Si la ruta contiene '..' o '.', lanzamos una excepción para evitar intentos de navegación no autorizados.
        if (in_array('..', $path, true) || in_array('.', $path, true)) {
            throw new ForbiddenException();
        }

        // Inicializamos las variables de la página y subpágina
        $page = $subpage = null;

        // Si existe el primer parámetro de la ruta, lo asignamos a la variable 'page'
        if (!empty($path[0])) {
            $page = $path[0];
        }

        // Si existe el segundo parámetro de la ruta, lo asignamos a la variable 'subpage'
        if (!empty($path[1])) {
            $subpage = $path[1];
        }

        // Pasamos las variables 'page' y 'subpage' a la vista
        $this->set(compact('page', 'subpage'));

        try {
            // Intenta renderizar la vista especificada por la ruta.
            return $this->render(implode('/', $path));
        } catch (MissingTemplateException $exception) {
            // Si la plantilla no existe y estamos en modo debug, lanzamos una excepción para ayudar en el desarrollo.
            if (Configure::read('debug')) {
                throw $exception;
            }
            // Si no estamos en modo debug, lanzamos una excepción de página no encontrada.
            throw new NotFoundException();
        }
    }
}
