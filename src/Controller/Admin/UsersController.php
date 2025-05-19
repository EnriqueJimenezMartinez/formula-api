<?php
declare(strict_types=1);

namespace App\Controller\Admin;

use App\Controller\AppController;
use Cake\Event\EventInterface;
use Cake\Http\Cookie\Cookie;
use DateTimeImmutable;

class UsersController extends AppController
{
    public function beforeFilter(EventInterface $event)
    {
        parent::beforeFilter($event);
        $this->Authentication->addUnauthenticatedActions(['login', 'add']);
    }

    public function index()
    {
        $query = $this->Users->find();
        $users = $this->paginate($query);

        $this->set(compact('users'));
    }

    public function view(?string $id = null)
    {
        $user = $this->Users->get($id, contain: ['News']);
        $this->set(compact('user'));
    }

    public function add()
    {
        $user = $this->Users->newEmptyEntity();
        if ($this->request->is('post')) {
            $user = $this->Users->patchEntity($user, $this->request->getData());
            if ($this->Users->save($user)) {
                $this->Flash->success(__('El usuario ha sido guardado.'));
                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('El usuario no pudo ser guardado. Por favor, intente nuevamente.'));
        }
        $this->set(compact('user'));
    }

    public function edit(?string $id = null)
    {
        $user = $this->Users->get($id, contain: []);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $user = $this->Users->patchEntity($user, $this->request->getData());
            if ($this->Users->save($user)) {
                $this->Flash->success(__('El usuario ha sido guardado.'));
                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('El usuario no pudo ser guardado. Por favor, intente nuevamente.'));
        }
        $this->set(compact('user'));
    }

    public function delete(?string $id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $user = $this->Users->get($id);
        if ($this->Users->delete($user)) {
            $this->Flash->success(__('El usuario ha sido eliminado.'));
        } else {
            $this->Flash->error(__('El usuario no pudo ser eliminado. Por favor, intente nuevamente.'));
        }

        return $this->redirect(['action' => 'index']);
    }

    public function login()
    {
        $this->request->allowMethod(['get', 'post']);
        $result = $this->Authentication->getResult();

        if ($result && $result->isValid()) {
            $user = $this->request->getAttribute('identity');

            // Crear cookie con valor válido (string)
            $cookie = new Cookie(
                'usuario_logueado',                // nombre
                $user->get('username') ?? 'user', // valor (string, no null)
                new DateTimeImmutable('+1 hour'), // expiración
                '/',                              
                null,
                false,
                true
            );

            // Añadir cookie a la respuesta
            $this->response = $this->response->withCookie($cookie);

            $redirect = $this->request->getQuery('redirect', [
                'controller' => 'Users',
                'action' => 'index',
            ]);

            return $this->redirect($redirect);
        }

        if ($this->request->is('post') && (!$result || !$result->isValid())) {
            $this->Flash->error(__('Usuario o contraseña inválidos'));
        }
    }

    public function logout()
    {
        $result = $this->Authentication->getResult();
        if ($result && $result->isValid()) {
            $this->Authentication->logout();

            // Crear cookie expirado para borrar cookie
            $expiredCookie = (new Cookie('usuario_logueado', ''))
                ->withExpired(new DateTimeImmutable('-1 hour'));

            $this->response = $this->response->withExpiredCookie($expiredCookie);

            return $this->redirect(['controller' => 'Users', 'action' => 'login']);
        }
    }
}
