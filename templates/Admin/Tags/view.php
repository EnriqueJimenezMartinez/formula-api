<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Tag $tag
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('_ACCIONES') ?></h4>
            <?= $this->Html->link(__('_EDITAR_TAGS'), ['action' => 'edit', $tag->id], ['class' => 'side-nav-item']) ?>
            <?= $this->Form->postLink(__('_BORRAR_TAGS'), ['action' => 'delete', $tag->id], ['confirm' => __('_CONFIRMACION_BORRAR', $tag->id), 'class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('_LISTA_TAGS'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('_ANADIR_TAG'), ['action' => 'add'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column column-80">
        <div class="tags view content">
            <h3><?= h($tag->name) ?></h3>
            <table>
                <tr>
                    <th><?= __('_NOMBRE') ?></th>
                    <td><?= h($tag->name) ?></td>
                </tr>
                <tr>
                    <th><?= __('_DESCRIPCION') ?></th>
                    <td><?= h($tag->description) ?></td>
                </tr>
                <tr>
                    <th><?= __('ID') ?></th>
                    <td><?= $this->Number->format($tag->id) ?></td>
                </tr>
                <tr>
                    <th><?= __('_CREADO') ?></th>
                    <td><?= h($tag->created) ?></td>
                </tr>
                <tr>
                    <th><?= __('_MODIFICADO') ?></th>
                    <td><?= h($tag->modified) ?></td>
                </tr>
            </table>
            <div class="related">
                <h4><?= __('_NOTICIAS_RELACIONADAS') ?></h4>
                <?php if (!empty($tag->news)) : ?>
                <div class="table-responsive">
                    <table>
                        <tr>
                            <th><?= __('ID') ?></th>
                            <th><?= __('_TITULO') ?></th>
                            <th><?= __('_SLUG') ?></th>
                            <th><?= __('_DESCRIPCION') ?></th>
                            <th><?= __('_USUARIO') ?></th>
                            <th><?= __('_ACTIVO') ?></th>
                            <th><?= __('_CREADO') ?></th>
                            <th><?= __('_MODIFICADO') ?></th>
                            <th class="actions"><?= __('_ACCIONES') ?></th>
                        </tr>
                        <?php foreach ($tag->news as $news) : ?>
                        <tr>
                            <td><?= h($news->id) ?></td>
                            <td><?= h($news->title) ?></td>
                            <td><?= h($news->slug) ?></td>
                            <td><?= h($news->description) ?></td>
                            <td><?= h($news->user_id) ?></td>
                            <td><?= $news->is_active ? __('Yes') : __('No') ?></td>
                            <td><?= h($news->created) ?></td>
                            <td><?= h($news->modified) ?></td>
                            <td class="actions">
                                <?= $this->Html->link(__('_VER'), ['controller' => 'News', 'action' => 'view', $news->id]) ?>
                                <?= $this->Html->link(__('_EDITAR_NEWS'), ['controller' => 'News', 'action' => 'edit', $news->id]) ?>
                                <?= $this->Form->postLink(
                                    __('_BORRAR_NEWS'),
                                    ['controller' => 'News', 'action' => 'delete', $news->id],
                                    [
                                        'method' => 'delete',
                                        'confirm' => __('_CONFIRMACION_BORRAR', $news->id),
                                    ]
                                ) ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </table>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
