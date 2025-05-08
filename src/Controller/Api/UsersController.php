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
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function initialize(): void
    {
        parent::initialize();
    }

    public function index()
    {
        $query = $this->Users->find();
        $users = $this->paginate($query);

        $this->set(compact('users'));

        $this->viewBuilder()->setOption('serialize', 'users');
    }

    public function login(): void
    {
        // Comprobamos credenciales usando el JwtAuthenticator
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

        // Construimos el payload del JWT
        $now   = time();
        $exp   = $now + 86400; // expira en 1 día
        $payload = [
            'sub' => $user->id,                     // subject: ID de usuario
            'iat' => $now,                          // issued at
            'exp' => $exp,                          // expiration
            'iss' => Configure::read('App.fullBaseUrl'), // issuer (opcional)
        ];

        // Generamos el token con nuestra clave secreta
        $token = JWT::encode(
            $payload,
            Configure::read('JWT_SECRET'),
            'HS256',
        );

        // Devolvemos respuesta JSON
        $this->respond(
            [
                'token' => $token,
                'expires' => date(DATE_ATOM, $exp),
                'user' => $user
            ],
            'success',   // status
            '',          // mensaje
            200,          // código HTTP
        );
    }

    /**
     * View method
     *
     * @param string|null $id User id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view(?string $id = null)
    {
        $user = $this->Users->get($id, contain: ['News']);
        $this->set(compact('user'));
        $this->viewBuilder()->setOption('serialize', 'user');
    }

    /**
     * Método que se ejecuta antes de cada acción del controlador.
     *
     * - Llama al método beforeFilter del controlador padre para conservar el comportamiento base.
     * - Indica al componente Authentication que la acción 'login' no requiere autenticación.
     *   Esto es importante para evitar que CakePHP bloquee el acceso al login por no tener token JWT.
     *
     * Este método evita un bucle infinito de autenticación: sin esto, al intentar acceder al login
     * sin estar autenticado, CakePHP lo redirigiría nuevamente a autenticarse, lo cual es un error.
     */
    public function beforeFilter(EventInterface $event)
    {
        parent::beforeFilter($event);
        $this->Authentication->addUnauthenticatedActions(['login']);
    }
}
