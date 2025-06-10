<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\User $user
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('_ACCIONES') ?></h4>
            <?= $this->Html->link(__('Editar Usuarios'), ['action' => 'edit', $user->id], ['class' => 'side-nav-item']) ?>
            <?= $this->Form->postLink(__('_BORRAR_USUARIOS'), ['action' => 'delete', $user->id], ['confirm' => __('_CONFIRMAR_BORRADO', $user->id), 'class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('Lista Usuarios'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('_NUEVO_USUARIO'), ['action' => 'add'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column column-80">
        <div class="users view content">
            <h3><?= h($user->name) ?></h3>
            <table>
                <tr>
                    <th><?= __('_NOMBRE') ?></th>
                    <td><?= h($user->name) ?></td>
                </tr>
                <tr>
                    <th><?= __('_APELLIDO') ?></th>
                    <td><?= h($user->surname) ?></td>
                </tr>
                <tr>
                    <th><?= __('_APODO') ?></th>
                    <td><?= h($user->nickname) ?></td>
                </tr>
                <tr>
                    <th><?= __('_EMAIL') ?></th>
                    <td><?= h($user->email) ?></td>
                </tr>
                <tr>
                    <th><?= __('id') ?></th>
                    <td><?= $this->Number->format($user->id) ?></td>
                </tr>
                <tr>
                    <th><?= __('_CREADO') ?></th>
                    <td><?= h($user->created) ?></td>
                </tr>
                <tr>
                    <th><?= __('_MODIFICADO') ?></th>
                    <td><?= h($user->modified) ?></td>
                </tr>
                <tr>
                    <th><?= __('_ACTIVO') ?></th>
                    <td><?= $user->is_active ? __('_SI') : __('_NO'); ?></td>
                </tr>
            </table>
            <div class="related">
                <h4><?= __('_NOTICIAS_RELACIONADAS') ?></h4>
                <?php if (!empty($user->news)) : ?>
                <div class="table-responsive">
                    <table>
                        <tr>
                            <th><?= __('_ID') ?></th>
                            <th><?= __('_TITULO') ?></th>
                            <th><?= __('_SLUG') ?></th>
                            <th><?= __('_DESCRIPCION') ?></th>
                            <th><?= __('_USER_ID') ?></th>
                            <th><?= __('_CREADO') ?></th>
                            <th><?= __('_MODIFICADO') ?></th>
                            <th class="actions"><?= __('_ACCIONES') ?></th>
                        </tr>
                        <?php foreach ($user->news as $news) : ?>
                        <tr>
                            <td><?= h($news->id) ?></td>
                            <td><?= h($news->title) ?></td>
                            <td><?= h($news->slug) ?></td>
                            <td><?= h($news->description) ?></td>
                            <td><?= h($news->user_id) ?></td>
                            <td><?= h($news->created) ?></td>
                            <td><?= h($news->modified) ?></td>
                            <td class="actions">
                                <?= $this->Html->link(__('_VER'), ['controller' => 'News', 'action' => 'view', $news->id]) ?>
                                <?= $this->Html->link(__('_EDITAR'), ['controller' => 'News', 'action' => 'edit', $news->id]) ?>
                                <?= $this->Form->postLink(
                                    __('_BORRAR'),
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

