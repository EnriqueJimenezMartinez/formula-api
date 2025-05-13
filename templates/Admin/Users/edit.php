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
            <?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $user->id],
                ['confirm' => __('_CONFIRMACION_BORRAR', $user->id), 'class' => 'side-nav-item']
            ) ?>
            <?= $this->Html->link(__('_LISTA_USUARIOS'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column column-80">
        <div class="users form content">
            <?= $this->Form->create($user) ?>
            <fieldset>
                <legend><?= __('_EDITAR_USUARIO') ?></legend>
                <?php
                    echo $this->Form->control('name', ['label' => __('_NOMBRE')]);
                    echo $this->Form->control('surname', ['label' => __('_APELLIDO')]);
                    echo $this->Form->control('nickname', ['label' => __('_APODO')]);
                    echo $this->Form->control('email', ['label' => __('_EMAIL')]);
                    echo $this->Form->control('password', ['label' => __('_CONTRASENA')]);
                    echo $this->Form->control('is_active', ['label' => __('_ACTIVO')]);
                ?>
            </fieldset>
            <?= $this->Form->button(__('_ENVIAR')) ?>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>
