<?php
/**
 * @var \App\View\AppView $this
 * @var iterable<\App\Model\Entity\Tag> $tags
 */
?>
<div class="tags index content">
    <?= $this->Html->link(__('New Tag'), ['action' => 'add'], ['class' => 'button float-right']) ?>
    <h3><?= __('_TAGS') ?></h3>
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th><?= $this->Paginator->sort('id', __('ID')) ?></th>
                    <th><?= $this->Paginator->sort('name', __('_NOMBRE')) ?></th>
                    <th><?= $this->Paginator->sort('description', __('_DESCRIPCION')) ?></th>
                    <th><?= $this->Paginator->sort('created', __('_CREADO')) ?></th>
                    <th><?= $this->Paginator->sort('modified', __('_MODIFICADO')) ?></th>
                    <th class="actions"><?= __('_ACCIONES') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($tags as $tag): ?>
                <tr>
                    <td><?= $this->Number->format($tag->id) ?></td>
                    <td><?= h($tag->name) ?></td>
                    <td><?= h($tag->description) ?></td>
                    <td><?= h($tag->created) ?></td>
                    <td><?= h($tag->modified) ?></td>
                    <td class="actions">
                        <?= $this->Html->link(__('_VER'), ['action' => 'view', $tag->id]) ?>
                        <?= $this->Html->link(__('_EDITAR'), ['action' => 'edit', $tag->id]) ?>
                        <?= $this->Form->postLink(
                            __('_BORRAR'),
                            ['action' => 'delete', $tag->id],
                            [
                                'method' => 'delete',
                                'confirm' => __("_CONFIRMACION_BORRAR", $tag->id),
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
            <?= $this->Paginator->first('<< ' . __('first')) ?>
            <?= $this->Paginator->prev('< ' . __('previous')) ?>
            <?= $this->Paginator->numbers() ?>
            <?= $this->Paginator->next(__('next') . ' >') ?>
            <?= $this->Paginator->last(__('last') . ' >>') ?>
        </ul>
        <p><?= $this->Paginator->counter(__('Page {{page}} of {{pages}}, showing {{current}} record(s) out of {{count}} total')) ?></p>
    </div>
</div>
