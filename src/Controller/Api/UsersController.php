<?php
declare(strict_types=1);

namespace App\Controller\Api;

use Authentication\PasswordHasher\DefaultPasswordHasher;
use Cake\Core\Configure;
use Cake\Event\EventInterface;
use Firebase\JWT\JWT;

/**
 * Users Controller
 *
 * @property \App\Model\Table\UsersTable $Users
 */
class UsersController extends ApiController
{
    /**
     * Initialize method
     *
     * @return void
     */
    public function initialize(): void
    {
        parent::initialize();
    }

    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void
     */
    public function index()
    {
        $query = $this->Users->find();
        $users = $this->paginate($query);

        $this->set(compact('users'));
        $this->viewBuilder()->setOption('serialize', 'users');
    }

    /**
     * Login method
     *
     * Handles user authentication and returns JWT if credentials are valid.
     *
     * @return void
     */
    public function login(): void
    {
        $data = $this->request->getData();

        if (empty($data['email']) || empty($data['password'])) {
            $this->respond(null, 'error', 'Email y password son requeridos', 400);

            return;
        }

        $user = $this->Users->find()
            ->where(['email' => $data['email']])
            ->first();

        $hasher = new DefaultPasswordHasher();
        if (!$user || !$hasher->check($data['password'], $user->password)) {
            $this->respond(null, 'error', 'Credenciales inválidas', 401);

            return;
        }

        $now = time();
        $exp = $now + 86400; // 1 día

        $payload = [
            'sub' => $user->id,
            'iat' => $now,
            'exp' => $exp,
            'iss' => Configure::read('App.fullBaseUrl'),
        ];

        $token = JWT::encode(
            $payload,
            Configure::read('JWT_SECRET'),
            'HS256',
        );

        $this->respond(
            [
                'token' => $token,
                'expires' => date(DATE_ATOM, $exp),
                'user' => $user,
            ],
            'success',
            '',
            200,
        );
    }

    /**
     * View method
     *
     * @param string|null $id User ID.
     * @return \Cake\Http\Response|null|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view(?string $id = null)
    {
        $user = $this->Users->get($id, ['contain' => ['News']]);
        $this->set(compact('user'));
        $this->viewBuilder()->setOption('serialize', 'user');
    }

    /**
     * Método ejecutado antes de cada acción del controlador.
     *
     * @param \Cake\Event\EventInterface $event Evento de filtro previo.
     * @return void
     */
    public function beforeFilter(EventInterface $event): void
    {
        parent::beforeFilter($event);
        $this->Authentication->addUnauthenticatedActions(['login']);
    }
}
