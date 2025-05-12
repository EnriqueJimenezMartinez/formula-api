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

use Cake\Controller\Controller;
use Cake\Event\EventInterface;

/**
 * Controlador de la aplicación
 *
 * Añade tus métodos a nivel de aplicación en la clase abajo, los controladores
 * los heredarán.
 *
 * @link https://book.cakephp.org/5/en/controllers.html#the-app-controller
 */
class AppController extends Controller
{
    /**
     * Método de inicialización.
     *
     * Usa este método para añadir código de inicialización común como la carga de componentes.
     *
     * Ejemplo: `$this->loadComponent('FormProtection');`
     *
     * @return void
     */
    public function initialize(): void
    {
        parent::initialize();

        $this->loadComponent('Flash');

        /*
         * Habilita el siguiente componente para la configuración recomendada de protección de formularios en CakePHP.
         * ver https://book.cakephp.org/5/en/controllers/components/form-protection.html
         */
        //$this->loadComponent('FormProtection');

        $this->loadComponent('Authentication.Authentication');
    }

    /**
     * Método ejecutado antes de cada acción del controlador.
     *
     * - Llama al método padre para mantener el comportamiento por defecto.
     * - Para todos los controladores, se hace pública la acción `login`.
     * - En el caso de los controladores fuera del prefijo `api`, también
     *   podrían hacerse públicas `index` y `view` (ejemplo comentado).
     *
     * @param \Cake\Event\EventInterface $event Evento de filtro previo.
     * @return void
     */
    public function beforeFilter(EventInterface $event)
    {
        parent::beforeFilter($event);
        // para todos los controladores de nuestra aplicación, hacer públicos los métodos index y view,
        // saltando la verificación de autenticación

        $prefix = $this->request->getAttribute('params')['prefix'] ?? null;
        if (strtolower((string)$prefix) !== 'api') {
            // Solo en Admin / Web: index y view públicas
            $this->Authentication->addUnauthenticatedActions([]);
        }
    }
}
