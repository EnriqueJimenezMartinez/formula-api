<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Entidad Tag (Etiqueta)
 *
 * Representa una etiqueta que puede estar asociada a una o varias noticias.
 *
 * @property int $id Identificador único de la etiqueta.
 * @property string $name Nombre de la etiqueta.
 * @property string $description Descripción de la etiqueta.
 * @property \Cake\I18n\DateTime $created Fecha de creación.
 * @property \Cake\I18n\DateTime $modified Fecha de última modificación.
 *
 * @property \App\Model\Entity\News[] $news Lista de noticias asociadas a esta etiqueta.
 */
class Tag extends Entity
{
    /**
     * Campos que pueden asignarse en masa usando newEntity() o patchEntity().
     *
     * @var array<string, bool>
     */
    protected array $_accessible = [
        'name' => true,
        'description' => true,
        'created' => true,
        'modified' => true,
        'news' => true, // Relación con las noticias asociadas.
    ];
}
