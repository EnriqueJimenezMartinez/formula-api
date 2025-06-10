<?php
/**
 * @var \App\View\AppView $this
 * @var iterable<\App\Model\Entity\User> $users
 */
?>
<div class="users index content">
    <?= $this->Html->link(__('_NUEVO_USUARIO'), ['action' => 'add'], ['class' => 'button float-right']) ?>
    <h3><?= __('_USUARIOS') ?></h3>
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th><?= $this->Paginator->sort('id','ID') ?></th>
                    <th><?= $this->Paginator->sort('name','Nombre') ?></th>
                    <th><?= $this->Paginator->sort('surname','Apellido') ?></th>
                    <th><?= $this->Paginator->sort('nickname','Nombre de Usuario') ?></th>
                    <th><?= $this->Paginator->sort('email') ?></th>
                    <th><?= $this->Paginator->sort('created','Creada') ?></th>
                    <th><?= $this->Paginator->sort('modified','Modificada') ?></th>
                    <th class="actions"><?= __('Acciones') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user): ?>
                <tr>
                    <td><?= $this->Number->format($user->id) ?></td>
                    <td><?= h($user->name) ?></td>
                    <td><?= h($user->surname) ?></td>
                    <td><?= h($user->nickname) ?></td>
                    <td><?= h($user->email) ?></td>
                    <td><?= h($user->created) ?></td>
                    <td><?= h($user->modified) ?></td>
                    <td class="actions">
                        <?= $this->Html->link(__('_VER'), ['action' => 'view', $user->id]) ?>
                        <?= $this->Html->link(__('_EDITAR'), ['action' => 'edit', $user->id]) ?>
                        <?= $this->Form->postLink(
                            __('_BORRAR'),
                            ['action' => 'delete', $user->id],
                            [
                                'method' => 'delete',
                                'confirm' => __("_CONFIRMACION_BORRAR", $user->id),
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
            <?= $this->Paginator->first('<< ' . __('primera')) ?>
            <?= $this->Paginator->prev('< ' . __('anterior')) ?>
            <?= $this->Paginator->numbers() ?>
            <?= $this->Paginator->next(__('siguiente') . ' >') ?>
            <?= $this->Paginator->last(__('última') . ' >>') ?>
        </ul>
        <p><?= $this->Paginator->counter(__('Página {{page}} de {{pages}},')) ?></p>
    </div>
</div>
