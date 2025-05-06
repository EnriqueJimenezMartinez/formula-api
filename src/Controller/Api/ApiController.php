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
 * Application Controller
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
     * e.g. `$this->loadComponent('FormProtection');`
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
                null,                // sin data
                'error',             // status
                'Credenciales inválidas', // mensaje
                401,                  // código HTTP
            );

            return;
        }
    }

    protected function respond(
        $data = null,
        string $status = 'success',
        string $message = '',
        int $code = 200,
    ): void {
        $this->response = $this->response
            ->withType('application/json')
            ->withStatus($code);
        $this->set(compact('status', 'data', 'message'));
    }
}
