<?php
declare(strict_types=1);

namespace App\Controller\Admin;

use App\Controller\AppController;
use Cake\Event\EventInterface;
use Cake\Http\Cookie\Cookie;
use Cake\Mailer\Mailer;

class UsersController extends AppController
{
    public function beforeFilter(EventInterface $event)
    {
        parent::beforeFilter($event);
        // Permitir acciones sin estar autenticado
        $this->Authentication->addUnauthenticatedActions(['login', 'verify2fa', 'add']);
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
        $session = $this->request->getSession();

        if ($this->request->is('post')) {
            $data = $this->request->getData();
            $user = $this->Users->findByEmail($data['email'])->first();

            if (!$user || !password_verify($data['password'], $user->password)) {
                $this->Flash->error(__('Usuario o contraseña inválidos'));
                return;
            }

            // Generar código 2FA de 6 dígitos (string con ceros a la izquierda)
            $code = str_pad((string)random_int(0, 999999), 6, '0', STR_PAD_LEFT);

            // Guardar código, user_id y expiración en sesión (5 minutos)
            $session->write('TwoFactor.code', $code);
            $session->write('TwoFactor.user_id', $user->id);
            $session->write('TwoFactor.expires', time() + 300);

            // Enviar email con el código
            $mailer = new Mailer('default');
            $mailer->setTo($user->email)
                ->setSubject('Código de verificación 2FA')
                ->deliver("Tu código de verificación es: $code. Expira en 5 minutos.");

            // Redirigir para ingresar código 2FA
            return $this->redirect(['action' => 'verify2fa']);
        }
    }

    public function verify2fa()
    {
        $session = $this->request->getSession();

        if ($this->request->is('post')) {
            $codeInput = $this->request->getData('code');
            $codeSaved = $session->read('TwoFactor.code');
            $userId = $session->read('TwoFactor.user_id');
            $expires = $session->read('TwoFactor.expires');

            if (!$codeSaved || !$userId || time() > $expires) {
                $this->Flash->error('El código 2FA ha expirado o no es válido.');
                return $this->redirect(['action' => 'login']);
            }

            if ($codeInput !== $codeSaved) {
                $this->Flash->error('Código 2FA incorrecto.');
                return;
            }

            // Código correcto: limpiar sesión 2FA
            $session->delete('TwoFactor');

            // Loguear usuario
            $user = $this->Users->get($userId);
            $this->Authentication->setIdentity($user);

            // Crear cookie usuario_logueado igual que antes
            $cookie = new Cookie(
                'usuario_logueado',
                $user->username ?? 'user',
                new \DateTimeImmutable('+1 hour'),
                '/',
                null,
                false,
                true
            );
            $this->response = $this->response->withCookie($cookie);

            // Redirigir al index (o dashboard)
            return $this->redirect(['action' => 'index']);
        }
    }

    public function logout()
    {
        $result = $this->Authentication->getResult();
        if ($result && $result->isValid()) {
            $this->Authentication->logout();

            $this->request->getSession()->delete('usuario_logueado');
            // Crear cookie expirado para borrar cookie
            $expiredCookie = (new Cookie('usuario_logueado', ''))
                ->withExpired(new \DateTimeImmutable('-1 hour'));

            $this->response = $this->response->withExpiredCookie($expiredCookie);

            return $this->redirect(['controller' => 'Users', 'action' => 'login']);
        }
    }
}
