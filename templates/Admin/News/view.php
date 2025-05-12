<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\News $news
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('_ACCIONES') ?></h4>
            <?= $this->Html->link(__('_EDITAR_NEWS'), ['action' => 'edit', $news->id], ['class' => 'side-nav-item']) ?>
            <?= $this->Form->postLink(__('_BORRAR_NEWS'), ['action' => 'delete', $news->id], ['confirm' => __('_CONFIRMACION_BORRAR', $news->id), 'class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('_LISTA_NOTICIAS'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('_NUEVA_NOTICIA'), ['action' => 'add'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column column-80">
        <div class="news view content">
            <h3><?= h($news->title) ?></h3>
            <table>
                <tr>
                    <th><?= __('_TITULO') ?></th>
                    <td><?= h($news->title) ?></td>
                </tr>
                <tr>
                    <th><?= __('_SLUG') ?></th>
                    <td><?= h($news->slug) ?></td>
                </tr>
                <tr>
                    <th><?= __('_USUARIO') ?></th>
                    <td>
                        <?= $news->hasValue('user') ? $this->Html->link($news->user->name, ['controller' => 'Users', 'action' => 'view', $news->user->id]) : '' ?>
                    </td>
                </tr>
                <tr>
                    <th><?= __('ID') ?></th>
                    <td><?= $this->Number->format($news->id) ?></td>
                </tr>
                <tr>
                    <th><?= __('_CREADO') ?></th>
                    <td><?= h($news->created) ?></td>
                </tr>
                <tr>
                    <th><?= __('_MODIFICADO') ?></th>
                    <td><?= h($news->modified) ?></td>
                </tr>
                <tr>
                    <th><?= __('_ACTIVO') ?></th>
                    <td><?= $news->is_active ? __('Yes') : __('No'); ?></td>
                </tr>
            </table>
            <div class="text">
                <strong><?= __('_DESCRIPCION') ?></strong>
                <blockquote>
                    <?= $this->Text->autoParagraph(h($news->description)); ?>
                </blockquote>
            </div>
            <div class="text">
                <strong><?= __('_CUERPO') ?></strong>
                <blockquote>
                    <?= $this->Text->autoParagraph(h($news->body)); ?>
                </blockquote>
            </div>
            <div class="related">
                <h4><?= __('_TAGS_RELACIONADOS') ?></h4>
                <?php if (!empty($news->tags)) : ?>
                <div class="table-responsive">
                    <table>
                        <tr>
                            <th><?= __('ID') ?></th>
                            <th><?= __('_NOMBRE') ?></th>
                            <th><?= __('_DESCRIPCION') ?></th>
                            <th><?= __('_CREADO') ?></th>
                            <th><?= __('_MODIFICADO') ?></th>
                            <th class="actions"><?= __('_ACCIONES_TAG') ?></th>
                        </tr>
                        <?php foreach ($news->tags as $tag) : ?>
                        <tr>
                            <td><?= h($tag->id) ?></td>
                            <td><?= h($tag->name) ?></td>
                            <td><?= h($tag->description) ?></td>
                            <td><?= h($tag->created) ?></td>
                            <td><?= h($tag->modified) ?></td>
                            <td class="actions">
                                <?= $this->Html->link(__('_VER'), ['controller' => 'Tags', 'action' => 'view', $tag->id]) ?>
                                <?= $this->Html->link(__('_EDITAR'), ['controller' => 'Tags', 'action' => 'edit', $tag->id]) ?>
                                <?= $this->Form->postLink(__('_BORRAR'), ['controller' => 'Tags', 'action' => 'delete', $tag->id], [
                                    'method' => 'delete',
                                    'confirm' => __('_CONFIRMACION_BORRAR', $tag->id),
                                ]) ?>
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
