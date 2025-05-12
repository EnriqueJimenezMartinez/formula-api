<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Entidad News (Noticia)
 *
 * Representa una noticia que contiene un título, una descripción, un cuerpo, y está asociada a un usuario y a varias etiquetas.
 *
 * @property int $id Identificador único de la noticia.
 * @property string $title Título de la noticia.
 * @property string $slug Slug (URL amigable) de la noticia.
 * @property string $description Descripción breve de la noticia.
 * @property string $body Cuerpo completo de la noticia.
 * @property int $user_id ID del usuario que creó la noticia.
 * @property bool $is_active Indica si la noticia está activa o no.
 * @property \Cake\I18n\DateTime $created Fecha de creación.
 * @property \Cake\I18n\DateTime $modified Fecha de última modificación.
 *
 * @property \App\Model\Entity\Tag[] $tags Lista de etiquetas asociadas a esta noticia.
 */
class News extends Entity
{
    /**
     * Campos que pueden asignarse en masa usando newEntity() o patchEntity().
     *
     * @var array<string, bool>
     */
    protected array $_accessible = [
        'title' => true, // Título de la noticia.
        'slug' => true, // Slug de la noticia.
        'description' => true, // Descripción breve.
        'body' => true, // Cuerpo de la noticia.
        'user_id' => true, // ID del usuario creador.
        'is_active' => true, // Si la noticia está activa.
        'created' => true, // Fecha de creación.
        'modified' => true, // Fecha de última modificación.
        'tags' => true, // Etiquetas asociadas.
        'image_file' => true, // Archivo de imagen asociado a la noticia.
    ];
}
