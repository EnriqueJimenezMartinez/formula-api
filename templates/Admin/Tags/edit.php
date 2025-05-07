<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Tag $tag
 * @var string[]|\Cake\Collection\CollectionInterface $news
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('_ACCIONES') ?></h4>
            <?= $this->Form->postLink(
                __('_BORRAR'),
                ['action' => 'delete', $tag->id],
                ['confirm' => __('_CONFIRMACION_BORRAR', $tag->id), 'class' => 'side-nav-item']
            ) ?>
            <?= $this->Html->link(__('_LISTA_TAGS'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column column-80">
        <div class="tags form content">
            <?= $this->Form->create($tag) ?>
            <fieldset>
                <legend><?= __('_EDITAR_TAGS') ?></legend>
                <?php
                    echo $this->Form->control('name', ['label'=>__('_NOMBRE')]);
                    echo $this->Form->control('description', ['label' =>__('_DESCRIPCION')]);
                    echo $this->Form->control('news._ids', ['options' => $news, 'label' =>__('_NOTICIAS_RELACIONADAS')]);
                ?>
            </fieldset>
            <?= $this->Form->button(__('_CREAR')) ?>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>
