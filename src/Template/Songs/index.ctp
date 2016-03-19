<nav class="large-2 medium-2 columns" id="actions-sidebar">
<?= $this->Form->create('search') ?>
<fieldset>
    <?php
        echo $this->Form->input('Search');
    ?>
</fieldset>
<?= $this->Form->button(__('Submit')) ?>
<?= $this->Form->end() ?>
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('New Song'), ['action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Song Instances'), ['controller' => 'SongInstances', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Song Instance'), ['controller' => 'SongInstances', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Song Tags'), ['controller' => 'SongTags', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Song Tag'), ['controller' => 'SongTags', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="songs index large-10 medium-10 columns content">

    <h3><?= __('Songs') ?></h3>
    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th class="id"><?= $this->Paginator->sort('id') ?></th>
                <th class="title"><?= $this->Paginator->sort('title') ?></th>
                <th class="written-by"><?= $this->Paginator->sort('written_by') ?></th>
                <th class="performed-by"><?= $this->Paginator->sort('performed_by') ?></th>
                <th class="actions"><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($songs as $song): ?>
            <tr>
                <td class="id"><?= $this->Number->format($song->id) ?></td>
                <td class="title"><?= h($song->title) ?></td>
                <td class="written-by"><?= h($song->written_by) ?></td>
                <td class="performed-by"><?= h($song->performed_by) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['action' => 'view', $song->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['action' => 'edit', $song->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $song->id], ['confirm' => __('Are you sure you want to delete # {0}?', $song->id)]) ?>
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
