<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Authentication\PasswordHasher\DefaultPasswordHasher;
use Cake\ORM\Entity;

/**
 * Entidad Usuario
 *
 * Representa un registro de la tabla `users`.
 *
 * @property int $id Identificador único del usuario.
 * @property string $name Nombre del usuario.
 * @property string $surname Apellido del usuario.
 * @property string $nickname Apodo o nombre de usuario.
 * @property string $email Correo electrónico.
 * @property string $password Contraseña (encriptada).
 * @property bool $is_active Indica si el usuario está activo.
 * @property \Cake\I18n\DateTime $created Fecha de creación del usuario.
 * @property \Cake\I18n\DateTime $modified Fecha de última modificación.
 */
class User extends Entity
{
    /**
     * Campos que pueden asignarse en masa usando newEntity() o patchEntity().
     *
     * Se recomienda por seguridad desactivar el acceso general ('*' => true)
     * y especificar los campos permitidos manualmente.
     *
     * @var array<string, bool>
     */
    protected array $_accessible = [
        'name' => true,
        'surname' => true,
        'nickname' => true,
        'email' => true,
        'password' => true,
        'is_active' => true,
        'created' => true,
        'modified' => true,
    ];

    /**
     * Campos que se ocultarán al convertir la entidad a JSON.
     *
     * Útil para no exponer datos sensibles como contraseñas.
     *
     * @var list<string>
     */
    protected array $_hidden = [
        'password',
    ];

    /**
     * Método para encriptar automáticamente la contraseña antes de guardarla.
     *
     * Se utiliza al establecer el valor del campo "password".
     *
     * @param string $password Contraseña en texto plano.
     * @return string|null Contraseña encriptada o null si está vacía.
     */
    protected function _setPassword(string $password): ?string
    {
        if (strlen($password) > 0) {
            return (new DefaultPasswordHasher())->hash($password);
        }

        return null;
    }
}
