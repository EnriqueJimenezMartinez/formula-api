<h2>Verificación en dos pasos (2FA)</h2>

<?= $this->Form->create(null) ?>
<?= $this->Form->control('code', ['label' => 'Código 2FA', 'required' => true]) ?>
<?= $this->Form->button('Verificar') ?>
<?= $this->Form->end() ?>

<?php if ($this->Flash->render()) : ?>
    <div class="flash-error"><?= $this->Flash->render() ?></div>
<?php endif; ?>
