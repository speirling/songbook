<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('New Song Performance'), ['action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Songs'), ['controller' => 'Songs', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Song'), ['controller' => 'Songs', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="songPerformances index large-9 medium-8 columns content">
    <h3><?= __('Song Performances') ?></h3>
    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th><?= $this->Paginator->sort('id') ?></th>
                <th><?= $this->Paginator->sort('song_id') ?></th>
                <th><?= $this->Paginator->sort('timestamp') ?></th>
                <th class="actions"><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($songPerformances as $songPerformance): ?>
            <tr>
                <td><?= $this->Number->format($songPerformance->id) ?></td>
                <td><?= $songPerformance->has('song') ? $this->Html->link($songPerformance->song->title, ['controller' => 'Songs', 'action' => 'view', $songPerformance->song->id]) : '' ?></td>
                <td><?= h($songPerformance->timestamp) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['action' => 'view', $songPerformance->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['action' => 'edit', $songPerformance->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $songPerformance->id], ['confirm' => __('Are you sure you want to delete # {0}?', $songPerformance->id)]) ?>
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
