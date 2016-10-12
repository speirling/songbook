<?php /* Template/Playlist/index.php */ ?>

<nav class="large-2 medium-3 columns" id="actions-sidebar">
    <?= $this->element('standard_menu') ?>
</nav>
<div class="playlists index large-10 medium-9 columns content">
    <h3><?= __('Playlists') ?></h3>
    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th><?= $this->Paginator->sort('id') ?></th>
                <th><?= $this->Paginator->sort('title') ?></th>
                <th><?= $this->Paginator->sort('performer_id') ?></th>
                <th class="actions"><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($playlists as $playlist): ?>
            <tr>
                <td><?= $this->Number->format($playlist->id) ?></td>
                <td><?= $this->Html->link(__($playlist->title), ['action' => 'view', $playlist->id]) ?></td>
                <td><?php
                if ($playlist->performer['nickname'] !== '') {
                    echo h($playlist->performer->nickname);
                 } else {
                    echo h($playlist->performer->name);
                 }
                 ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('Edit'), ['action' => 'edit', $playlist->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $playlist->id], ['confirm' => __('Are you sure you want to delete # {0}?', $playlist->id)]) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <div class="paginator">
        <ul class="pagination">
            <?= $this->Paginator->prev('< ' . __('previous')) ?>
            <?= $this->Paginator->numbers() ?>
            <?= $this->Paginator->next(__('next') . ' >') ?>
        </ul>
        <p><?= $this->Paginator->counter() ?></p>
    </div>
</div>
