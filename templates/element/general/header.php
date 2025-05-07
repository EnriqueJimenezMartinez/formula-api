<?php
/**
 * @var \App\View\AppView $this
*/
?>
<nav class="top-nav">
    <div class="top-nav-title">
        <span><?=__('_BLOG')?></span>
    </div>
    <div class="top-nav-links">
        <?= $this->Html->link(__('_USUARIOS'), ['controller' => 'Users', 'action' => 'index'])?>
        <?= $this->Html->link(__('_NOTICIAS'), ['controller' => 'News', 'action' => 'index'])?>
        <?= $this->Html->link(__('_TAGS'), ['controller' => 'Tags', 'action' => 'index'])?>
    </div>
</nav>
