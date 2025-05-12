<?php
declare(strict_types=1);

namespace App\Controller\Admin;

use App\Controller\AppController;
use Cake\Event\EventInterface;

/**
 * Controlador de Usuarios
 *
 * @property \App\Model\Table\UsersTable $Users
 */
class UsersController extends AppController
{
    /**
     * Método Index
     *
     * @return \Cake\Http\Response|null|void Renderiza la vista
     */
    public function index()
    {
        $query = $this->Users->find();
        $users = $this->paginate($query);

        $this->set(compact('users'));
    }

    /**
     * Método View
     *
     * @param string|null $id Id del usuario.
     * @return \Cake\Http\Response|null|void Renderiza la vista
     * @throws \Cake\Datasource\Exception\RecordNotFoundException Si no se encuentra el registro.
     */
    public function view(?string $id = null)
    {
        $user = $this->Users->get($id, contain: ['News']);
        $this->set(compact('user'));
    }

    /**
     * Método Add
     *
     * @return \Cake\Http\Response|null|void Redirige en caso de éxito, renderiza la vista en caso contrario.
     */
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

    /**
     * Método Edit
     *
     * @param string|null $id Id del usuario.
     * @return \Cake\Http\Response|null|void Redirige en caso de éxito, renderiza la vista en caso contrario.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException Si no se encuentra el registro.
     */
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

    /**
     * Método Delete
     *
     * @param string|null $id Id del usuario.
     * @return \Cake\Http\Response|null Redirige al índice.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException Si no se encuentra el registro.
     */
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

    /**
     * Método que se ejecuta antes de cada acción del controlador.
     *
     * - Llama al método beforeFilter del controlador padre para conservar el comportamiento base.
     * - Especifica qué acciones pueden ejecutarse sin necesidad de autenticación (sin token JWT o sesión).
     * - En este caso, se permite el acceso público a 'login' y 'add', útil para permitir:
     *   - Que usuarios se autentiquen (login).
     *   - Que nuevos usuarios se registren (add).
     *
     * Esto previene errores como bucles de redirección o respuestas 401 cuando el cliente aún no está autenticado.
     */
    public function beforeFilter(EventInterface $event)
    {
        parent::beforeFilter($event);
        // Configura la acción de login para no requerir autenticación, evitando
        // el problema de bucles de redirección
        $this->Authentication->addUnauthenticatedActions(['login', 'add']);
    }

    /**
     * Acción de login para usuarios del panel Admin.
     *
     * - Permite métodos GET y POST.
     * - Si ya está autenticado (login exitoso), redirige al destino deseado o por defecto a /articles.
     * - Si el usuario envía el formulario y falla la autenticación, muestra un mensaje de error.
     *
     * @return \Cake\Http\Response|null Redirección en caso de éxito o null para seguir mostrando el formulario
     */
    public function login()
    {
        $this->request->allowMethod(['get', 'post']);
        $result = $this->Authentication->getResult();
        // Independientemente de si es POST o GET, redirige si el usuario está logueado
        if ($result && $result->isValid()) {
            $redirect = $this->request->getQuery('redirect', [
                'controller' => 'users',
                'action' => 'index',
            ]);

            return $this->redirect($redirect);
        }
        if ($this->request->is('post') && !$result->isValid()) {
            $this->Flash->error(__('Usuario o contraseña inválidos'));
        }
    }

    /**
     * Acción de logout para usuarios autenticados por sesión.
     *
     * - Verifica si el usuario está autenticado.
     * - Si lo está, cierra la sesión y redirige a la pantalla de login.
     *
     * Esto solo aplica en interfaces web con sesión (no para APIs con JWT).
     *
     * @return \Cake\Http\Response|null Redirección a login o null si el usuario no estaba autenticado
     */
    public function logout()
    {
        $result = $this->Authentication->getResult();
        // Independientemente de si es POST o GET, redirige si el usuario está logueado
        if ($result && $result->isValid()) {
            $this->Authentication->logout();

            return $this->redirect(['controller' => 'Users', 'action' => 'login']);
        }
    }
}
