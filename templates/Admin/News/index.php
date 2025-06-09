<?php
/**
 * @var \App\View\AppView $this
 * @var iterable<\App\Model\Entity\News> $news
 */
?>
<div class="news index content">
    <?= $this->Html->link(__('_NUEVA_NOTICIA'), ['action' => 'add'], ['class' => 'button float-right']) ?>
    <h3><?= __('_NOTICIAS') ?></h3>
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th><?= $this->Paginator->sort('id','ID') ?></th>
                    <th><?= $this->Paginator->sort('title', 'Título') ?></th>
                    <th><?= $this->Paginator->sort('slug', 'Slug') ?></th>
                    <th><?= $this->Paginator->sort('user_id','Usuario') ?></th>
                    <th><?= $this->Paginator->sort('created','Creada') ?></th>
                    <th><?= $this->Paginator->sort('modified','Modificada') ?></th>
                    <th class="actions"><?= __('Acciones') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($news as $news): ?>
                <tr>
                    <td><?= $this->Number->format($news->id) ?></td>
                    <td><?= h($news->title) ?></td>
                    <td><?= h($news->slug) ?></td>
                    <td><?= $news->hasValue('user') ? $this->Html->link($news->user->name, ['controller' => 'Users', 'action' => 'view', $news->user->id]) : '' ?></td>
                    <td><?= h($news->created) ?></td>
                    <td><?= h($news->modified) ?></td>
                    <td class="actions">
                        <?= $this->Html->link(__('_VER'), ['action' => 'view', $news->id]) ?>
                        <?= $this->Html->link(__('_EDITAR'), ['action' => 'edit', $news->id]) ?>
                        <?= $this->Form->postLink(
                            __('_BORRAR'),
                            ['action' => 'delete', $news->id],
                            [
                                'method' => 'delete',
                                'confirm' => __("_CONFIRMACION_BORRAR", $news->id),
                            ]
                        ) ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <div class="paginator">
        <ul class="pagination">
            <?= $this->Paginator->first('<< ' . __('primero')) ?>
            <?= $this->Paginator->prev('< ' . __('anterior')) ?>
            <?= $this->Paginator->numbers() ?>
            <?= $this->Paginator->next(__('siguiente') . ' >') ?>
            <?= $this->Paginator->last(__('ultimo') . ' >>') ?>
        </ul>
        <p><?= $this->Paginator->counter(__('Página {{page}} de {{pages}}')) ?></p>
    </div>
</div>
