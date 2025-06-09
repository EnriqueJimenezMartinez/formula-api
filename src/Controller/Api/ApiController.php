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
 * @since     0.2.9
 * @license   https://opensource.org/licenses/mit-license.php MIT License
 */
namespace App\Controller\Api;

use App\Controller\AppController as BaseController;
use Cake\Event\EventInterface;
use Cake\View\JsonView;

/**
 * Api Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @link https://book.cakephp.org/5/en/controllers.html#the-app-controller
 */
class ApiController extends BaseController
{
    /**
     * Initialization hook method.
     *
     * Use this method to add common initialization code like loading components.
     *
     * e.g. `$this->loadComponent('FormProtection');w`
     *
     * @return void
     */
    public function initialize(): void
    {
        parent::initialize();

        $this->loadComponent('Flash');

        /*
         * Enable the following component for recommended CakePHP form protection settings.
         * see https://book.cakephp.org/5/en/controllers/components/form-protection.html
         */
        //$this->loadComponent('FormProtection');

        $this->loadComponent('Authentication.Authentication');
    }

    /**
     * Configura la vista para que devuelva respuestas en formato JSON
     * Verfica si el usuario está autenticado con ela tributo identity del request
     * Si no hay encuentra la identidad muestra el error 401
     */
    public function beforeFilter(EventInterface $event)
    {
        parent::beforeFilter($event);
        $this->viewBuilder()
            ->setClassName(JsonView::class)
            ->disableAutoLayout()
            ->setOption('serialize', ['status', 'data', 'message']);
            $identity = $this->getRequest()->getAttribute('identity');
        if (!$identity) {
            // Lanza la excepción que nuestro ApiExceptionRenderer convierte en 401 JSON
            $this->respond(
                null, // sin data
                'error', // status
                'Credenciales inválidas', // mensaje
                401, // código HTTP
            );

            return;
        }
    }

    /**
     * Método auxiliar para generar respuestas JSON unificadas.
     *
     * Este método:
     * - Define el tipo de contenido como 'application/json'.
     * - Establece el código de estado HTTP de la respuesta (200, 401, 400, etc.).
     * - Establece las variables 'status', 'data' y 'message' que serán serializadas automáticamente en JSON.
     *
     * @param mixed $data Datos que se incluirán en la respuesta (puede ser array, objeto o null).
     * @param string $status Estado de la respuesta ('success', 'error', etc.).
     * @param string $message Mensaje explicativo opcional.
     * @param int $code Código de estado HTTP (por defecto 200).
     */
    protected function respond(
        mixed $data = null,
        string $status = 'success',
        string $message = '',
        int $code = 200,
    ): void {
        $this->response = $this->response
            ->withType('application/json')
            ->withStatus($code)
            ->withHeader('Access-Control-Allow-Origin', 'https://formula-front.vercel.app')
            ->withHeader('Access-Control-Allow-Methods', 'GET, POST, OPTIONS, PUT, DELETE')
            ->withHeader('Access-Control-Allow-Headers', 'Origin, Content-Type, Accept, Authorization');
        $this->set(compact('status', 'data','message'));
    }
}
