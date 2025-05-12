<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\News $news
 * @var \Cake\Collection\CollectionInterface|string[] $users
 * @var \Cake\Collection\CollectionInterface|string[] $tags
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('_ACCIONES') ?></h4>
            <?= $this->Html->link(__('_LISTA_NEWS'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column column-80">
        <div class="news form content">
            <?= $this->Form->create($news, ['type' => 'file']) ?>
            <fieldset>
                <legend><?= __('_ANADIR_NOTICIA') ?></legend>
                <?php
                    echo $this->Form->control('title', ['label' => __('_TITULO')]);
                    echo $this->Form->control('slug', ['label' => __('_SLUG')]);
                    echo $this->Form->control('description', ['label' => __('_DESCRIPCION')]);
                    echo $this->Form->control('body', ['label' => __('_CUERPO')]);
                    echo $this->Form->control('user_id', ['options' => $users, 'label' => __('_USER_ID')]);
                    echo $this->Form->control('is_active', ['label' => __('_ACTIVO')]);
                    echo $this->Form->control('tags._ids', ['options' => $tags, 'label' => __('_TAGS_RELACIONADOS')]);
                    echo $this->Form->control('image_file', ['type' => 'file', 'label' => 'Imagen (opcional)']);
                ?>
            </fieldset>
            <?= $this->Form->button(__('_ENVIAR')) ?>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>
