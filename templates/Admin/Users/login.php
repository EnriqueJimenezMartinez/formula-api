<div class="users form">
    <?= $this->Flash->render() ?>
    <h3><?= __('_LOGIN') ?></h3>
    <?= $this->Form->create() ?>
    <fieldset>
        <legend><?= __('_POR_FAVOR_INGRESE_USUARIO_CONTRASENA') ?></legend>
        <?= $this->Form->control('email', ['required' => true, 'label' => __('_EMAIL')]) ?>
        <?= $this->Form->control('password', ['required' => true, 'label' => __('_CONTRASENA')]) ?>
    </fieldset>
    <?= $this->Form->submit(__('_INICIAR_SESION')); ?>
    <?= $this->Form->end() ?>

    <?= $this->Html->link(__('_ANADIR_USUARIO'), ['action' => 'add']) ?>
</div>
