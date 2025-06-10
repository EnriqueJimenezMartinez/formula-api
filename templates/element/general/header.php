<?php
/**
 * @var \App\View\AppView $this
*/
?>
<nav class="top-nav">
    <div class="top-nav-title">
        <span style="display:inline-block; font-size:2rem; font-weight:bold; color:#dc3545; background-color:#f8d7da; padding:0.5rem 1rem; border-radius:1rem; box-shadow:0 0 10px rgba(220,53,69,0.3);">
    <?= __('ECHOBLOG') ?>
</span>

    </div>
    <div class="top-nav-links">
        <?= $this->Html->link(__('_USUARIOS'), ['controller' => 'Users', 'action' => 'index'])?>
        <?= $this->Html->link(__('_NOTICIAS'), ['controller' => 'News', 'action' => 'index'])?>
        <?= $this->Html->link(__('_TAGS'), ['controller' => 'Tags', 'action' => 'index'])?>
        <?php
            $session = $this->getRequest()->getSession();
            if ($session->read('Auth')): ?>
                <?= $this->Html->link(
                    __('Cerrar Sesión'),
                    ['action' => 'logout'],
                    ['class' => 'button float-right', 'style' => 'color: white;']
                ) ?>
        <?php endif; ?>
    </div>
</nav>
