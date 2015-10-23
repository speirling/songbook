<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('New Song Instance'), ['action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Songs'), ['controller' => 'Songs', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Song'), ['controller' => 'Songs', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Performers'), ['controller' => 'Performers', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Performer'), ['controller' => 'Performers', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="songInstances index large-9 medium-8 columns content">
    <h3><?= __('Song Instances') ?></h3>
    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th><?= $this->Paginator->sort('id') ?></th>
                <th><?= $this->Paginator->sort('song_id') ?></th>
                <th><?= $this->Paginator->sort('performer_id') ?></th>
                <th><?= $this->Paginator->sort('key') ?></th>
                <th class="actions"><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($songInstances as $songInstance): ?>
            <tr>
                <td><?= $this->Number->format($songInstance->id) ?></td>
                <td><?= $songInstance->has('song') ? $this->Html->link($songInstance->song->title, ['controller' => 'Songs', 'action' => 'view', $songInstance->song->id]) : '' ?></td>
                <td><?= $songInstance->has('performer') ? $this->Html->link($songInstance->performer->name, ['controller' => 'Performers', 'action' => 'view', $songInstance->performer->id]) : '' ?></td>
                <td><?= h($songInstance->key) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['action' => 'view', $songInstance->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['action' => 'edit', $songInstance->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $songInstance->id], ['confirm' => __('Are you sure you want to delete # {0}?', $songInstance->id)]) ?>
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
