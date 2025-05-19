<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Modelo de la tabla News (Noticias)
 *
 * @property \App\Model\Table\TagsTable&\Cake\ORM\Association\BelongsToMany $Tags Asociación con etiquetas (Tags)
 *
 * Métodos para gestionar entidades de noticias.
 * @method \App\Model\Entity\News newEmptyEntity() Crear una nueva entidad vacía.
 * @method \App\Model\Entity\News newEntity(array $data, array $options = []) Crear una entidad con datos.
 * @method array<\App\Model\Entity\News> newEntities(array $data, array $options = []) Crear múltiples entidades.
 * @method \App\Model\Entity\News get(...) Obtener una entidad por clave primaria.
 * @method \App\Model\Entity\News findOrCreate(...) Buscar o crear una entidad.
 * @method \App\Model\Entity\News patchEntity(...) Actualizar una entidad existente con nuevos datos.
 * @method array<\App\Model\Entity\News> patchEntities(...) Actualizar múltiples entidades.
 * @method \App\Model\Entity\News|false save(...) Guardar una entidad, devuelve false si falla.
 * @method \App\Model\Entity\News saveOrFail(...) Guardar o lanzar excepción si falla.
 * @method iterable<\App\Model\Entity\News>|... saveMany(...) Guardar múltiples entidades.
 * @method iterable<\App\Model\Entity\News>|... saveManyOrFail(...) Guardar múltiples o lanzar excepción si falla.
 * @method iterable<\App\Model\Entity\News>|... deleteMany(...) Eliminar múltiples entidades.
 * @method iterable<\App\Model\Entity\News>|... deleteManyOrFail(...) Eliminar múltiples o lanzar excepción si falla.
 * @mixin \Cake\ORM\Behavior\TimestampBehavior Comportamiento para campos created y modified.
 */
class NewsTable extends Table
{
    /**
     * Método de inicialización.
     *
     * Configura la tabla, clave primaria, campo de visualización
     * y relaciones con otras tablas.
     *
     * @param array<string, mixed> $config Configuración de la tabla.
     * @return void
     */
    public function initialize(array $config): void
    {
        parent::initialize($config);

        // Nombre de la tabla en la base de datos
        $this->setTable('news');
        // Campo que se mostrará por defecto al representar una entidad
        $this->setDisplayField('title');
        // Clave primaria
        $this->setPrimaryKey('id');

        // Añade el comportamiento Timestamp (created/modified)
        $this->addBehavior('Timestamp');

        // Relación: cada noticia pertenece a un usuario
        $this->belongsTo('Users', [
            'foreignKey' => 'user_id',
            'joinType' => 'INNER',
        ]);

        // Relación muchos a muchos con etiquetas
        $this->belongsToMany('Tags', [
            'foreignKey' => 'news_id',
            'targetForeignKey' => 'tag_id',
            'joinTable' => 'news_tags',
        ]);
    }

    /**
     * Reglas de validación por defecto.
     *
     * Define qué campos son obligatorios, sus formatos y restricciones.
     *
     * @param \Cake\Validation\Validator $validator Instancia del validador.
     * @return \Cake\Validation\Validator Validador configurado.
     */
   public function validationDefault(Validator $validator): Validator
{
    $validator
        ->scalar('title', 'El título debe ser una cadena de texto')
        ->maxLength('title', 255, 'El título no puede tener más de 255 caracteres')
        ->requirePresence('title', 'create', 'El título es obligatorio')
        ->notEmptyString('title', 'El título no puede estar vacío')
        ->add('title', 'unique', [
            'rule' => 'validateUnique',
            'provider' => 'table',
            'message' => 'Este título ya está en uso',
        ]);

    $validator
        ->scalar('slug', 'El slug debe ser una cadena de texto')
        ->maxLength('slug', 255, 'El slug no puede tener más de 255 caracteres')
        ->requirePresence('slug', 'create', 'El slug es obligatorio')
        ->notEmptyString('slug', 'El slug no puede estar vacío')
        ->add('slug', 'unique', [
            'rule' => 'validateUnique',
            'provider' => 'table',
            'message' => 'Este slug ya está en uso',
        ]);

    $validator
        ->scalar('description', 'La descripción debe ser una cadena de texto')
        ->requirePresence('description', 'create', 'La descripción es obligatoria')
        ->notEmptyString('description', 'La descripción no puede estar vacía');

    $validator
        ->scalar('body', 'El cuerpo debe ser una cadena de texto')
        ->requirePresence('body', 'create', 'El cuerpo de la noticia es obligatorio')
        ->notEmptyString('body', 'El cuerpo de la noticia no puede estar vacío');

    $validator
        ->integer('user_id', 'El ID de usuario debe ser un número entero')
        ->notEmptyString('user_id', 'El ID de usuario no puede estar vacío');

    $validator
        ->boolean('is_active', 'El campo de publicación debe ser verdadero o falso')
        ->requirePresence('is_active', 'create', 'El campo de publicación es obligatorio')
        ->notEmptyString('is_active', 'El campo de publicación no puede estar vacío');

    return $validator;
}


    /**
     * Reglas de integridad de la aplicación.
     *
     * Se asegura de que los títulos y slugs sean únicos y que la noticia
     * pertenezca a un usuario existente.
     *
     * @param \Cake\ORM\RulesChecker $rules Objeto Rules a modificar.
     * @return \Cake\ORM\RulesChecker Reglas actualizadas.
     */
    public function buildRules(RulesChecker $rules): RulesChecker
    {
        $rules->add($rules->isUnique(['title']), ['errorField' => 'title']); // Título único
        $rules->add($rules->isUnique(['slug']), ['errorField' => 'slug']); // Slug único
        $rules->add($rules->existsIn(['user_id'], 'Users'), ['errorField' => 'user_id']); // Debe haber usuario asociado

        return $rules;
    }
}
