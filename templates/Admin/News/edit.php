<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\News $news
 * @var string[]|\Cake\Collection\CollectionInterface $users
 * @var string[]|\Cake\Collection\CollectionInterface $tags
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $news->id],
                ['confirm' => __('Are you sure you want to delete # {0}?', $news->id), 'class' => 'side-nav-item']
            ) ?>
            <?= $this->Html->link(__('List News'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column column-80">
        <div class="news form content">
            <?= $this->Form->create($news) ?>
            <fieldset>
                <legend><?= __('Edit News') ?></legend>
                <?php
                    echo $this->Form->control('title');
                    echo $this->Form->control('slug');
                    echo $this->Form->control('description');
                    echo $this->Form->control('body');
                    echo $this->Form->control('user_id', ['options' => $users]);
                    echo $this->Form->control('is_active');
                    echo $this->Form->control('tags._ids', ['options' => $tags]);
                    echo $this->Form->control('image_file', ['type' => 'file', 'label' => 'Imagen (opcional)']);
                ?>
            </fieldset>
            <?= $this->Form->button(__('Submit')) ?>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>
