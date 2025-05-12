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
            ->scalar('title') // Campo de texto
            ->maxLength('title', 255)
            ->requirePresence('title', 'create') // Requiere en creación
            ->notEmptyString('title') // No puede estar vacío
            ->add('title', 'unique', ['rule' => 'validateUnique', 'provider' => 'table']); // Debe ser único

        $validator
            ->scalar('slug')
            ->maxLength('slug', 255)
            ->requirePresence('slug', 'create')
            ->notEmptyString('slug')
            ->add('slug', 'unique', ['rule' => 'validateUnique', 'provider' => 'table']);

        $validator
            ->scalar('description') // Campo obligatorio
            ->requirePresence('description', 'create')
            ->notEmptyString('description');

        $validator
            ->scalar('body') // Cuerpo de la noticia
            ->requirePresence('body', 'create')
            ->notEmptyString('body');

        $validator
            ->integer('user_id') // ID del usuario autor
            ->notEmptyString('user_id');

        $validator
            ->boolean('is_active') // Estado de publicación
            ->requirePresence('is_active', 'create')
            ->notEmptyString('is_active');

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
