<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Modelo de la tabla Users
 *
 * Métodos para trabajar con entidades de usuario.
 *
 * @method \App\Model\Entity\User newEmptyEntity() Crear una nueva entidad vacía.
 * @method \App\Model\Entity\User newEntity(array $data, array $options = []) Crear una nueva entidad con datos.
 * @method array<\App\Model\Entity\User> newEntities(array $data, array $options = []) Crear múltiples entidades con datos.
 * @method \App\Model\Entity\User get(mixed $primaryKey, ...) Obtener una entidad por clave primaria.
 * @method \App\Model\Entity\User findOrCreate($search, ?callable $callback = null, array $options = []) Buscar o crear una entidad.
 * @method \App\Model\Entity\User patchEntity(...) Actualizar una entidad existente con nuevos datos.
 * @method array<\App\Model\Entity\User> patchEntities(...) Actualizar múltiples entidades.
 * @method \App\Model\Entity\User|false save(...) Guardar una entidad, devuelve false si falla.
 * @method \App\Model\Entity\User saveOrFail(...) Guardar o lanzar una excepción si falla.
 * @method iterable<\App\Model\Entity\User>|... saveMany(...) Guardar múltiples entidades.
 * @method iterable<\App\Model\Entity\User>|... saveManyOrFail(...) Guardar múltiples entidades o lanzar excepción si falla.
 * @method iterable<\App\Model\Entity\User>|... deleteMany(...) Eliminar múltiples entidades.
 * @method iterable<\App\Model\Entity\User>|... deleteManyOrFail(...) Eliminar múltiples entidades o lanzar excepción si falla.
 * @mixin \Cake\ORM\Behavior\TimestampBehavior Comportamiento para gestionar automáticamente campos de tiempo (created/modified).
 */
class UsersTable extends Table
{
    /**
     * Método de inicialización.
     *
     * Configura la tabla, el campo de visualización, la clave primaria
     * y relaciones con otras tablas.
     *
     * @param array<string, mixed> $config Configuración para la tabla.
     * @return void
     */
    public function initialize(array $config): void
    {
        parent::initialize($config);

        // Nombre de la tabla en la base de datos
        $this->setTable('users');
        // Campo que se mostrará por defecto al representar una entidad
        $this->setDisplayField('name');
        // Clave primaria de la tabla
        $this->setPrimaryKey('id');

        // Añade el comportamiento Timestamp (campos created y modified)
        $this->addBehavior('Timestamp');

        // Relación: un usuario tiene muchas noticias
        $this->hasMany('News', [
            'foreignKey' => 'user_id',
        ]);
    }

    /**
     * Reglas de validación por defecto.
     *
     * Define qué campos son obligatorios, su formato y otras restricciones.
     *
     * @param \Cake\Validation\Validator $validator Instancia del validador.
     * @return \Cake\Validation\Validator Validador configurado.
     */
    public function validationDefault(Validator $validator): Validator
{
    $validator
        ->scalar('name', 'El nombre debe ser una cadena de texto')
        ->maxLength('name', 50, 'El nombre no puede tener más de 50 caracteres')
        ->requirePresence('name', 'create', 'El nombre es obligatorio')
        ->notEmptyString('name', 'El nombre no puede estar vacío');

    $validator
        ->scalar('surname', 'El apellido debe ser una cadena de texto')
        ->maxLength('surname', 150, 'El apellido no puede tener más de 150 caracteres')
        ->requirePresence('surname', 'create', 'El apellido es obligatorio')
        ->notEmptyString('surname', 'El apellido no puede estar vacío');

    $validator
        ->scalar('nickname', 'El apodo debe ser una cadena de texto')
        ->maxLength('nickname', 50, 'El apodo no puede tener más de 50 caracteres')
        ->requirePresence('nickname', 'create', 'El apodo es obligatorio')
        ->notEmptyString('nickname', 'El apodo no puede estar vacío')
        ->add('nickname', 'unique', [
            'rule' => 'validateUnique',
            'provider' => 'table',
            'message' => 'Este apodo ya está en uso',
        ]);

    $validator
        ->email('email', false, 'El formato del correo electrónico no es válido')
        ->requirePresence('email', 'create', 'El correo electrónico es obligatorio')
        ->notEmptyString('email', 'El correo electrónico no puede estar vacío')
        ->add('email', 'unique', [
            'rule' => 'validateUnique',
            'provider' => 'table',
            'message' => 'Este correo electrónico ya está registrado',
        ]);

    $validator
        ->scalar('password', 'La contraseña debe ser una cadena de texto')
        ->maxLength('password', 255, 'La contraseña no puede tener más de 255 caracteres')
        ->requirePresence('password', 'create', 'La contraseña es obligatoria')
        ->notEmptyString('password', 'La contraseña no puede estar vacía');

    $validator
        ->boolean('is_active', 'El campo "activo" debe ser verdadero o falso')
        ->requirePresence('is_active', 'create', 'El campo "activo" es obligatorio')
        ->notEmptyString('is_active', 'El campo "activo" no puede estar vacío');

    return $validator;
}


    /**
     * Reglas de integridad para la aplicación.
     *
     * Se asegura de que ciertos campos sean únicos a nivel de base de datos.
     *
     * @param \Cake\ORM\RulesChecker $rules Objeto rules a modificar.
     * @return \Cake\ORM\RulesChecker Objeto modificado con reglas añadidas.
     */
    public function buildRules(RulesChecker $rules): RulesChecker
    {
        $rules->add($rules->isUnique(['nickname']), ['errorField' => 'nickname']); // Nick único
        $rules->add($rules->isUnique(['email']), ['errorField' => 'email']); // Email único

        return $rules;
    }
}
