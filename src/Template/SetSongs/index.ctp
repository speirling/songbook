<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('New Set Song'), ['action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Songs'), ['controller' => 'Songs', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Song'), ['controller' => 'Songs', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Sets'), ['controller' => 'Sets', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Set'), ['controller' => 'Sets', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="setSongs index large-9 medium-8 columns content">
    <h3><?= __('Set Songs') ?></h3>
    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th><?= $this->Paginator->sort('id') ?></th>
                <th><?= $this->Paginator->sort('song_id') ?></th>
                <th><?= $this->Paginator->sort('set_id') ?></th>
                <th><?= $this->Paginator->sort('order') ?></th>
                <th class="actions"><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($setSongs as $setSong): ?>
            <tr>
                <td><?= $this->Number->format($setSong->id) ?></td>
                <td><?= $setSong->has('song') ? $this->Html->link($setSong->song->title, ['controller' => 'Songs', 'action' => 'view', $setSong->song->id]) : '' ?></td>
                <td><?= $setSong->has('set') ? $this->Html->link($setSong->set->title, ['controller' => 'Sets', 'action' => 'view', $setSong->set->id]) : '' ?></td>
                <td><?= $this->Number->format($setSong->order) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['action' => 'view', $setSong->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['action' => 'edit', $setSong->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $setSong->id], ['confirm' => __('Are you sure you want to delete # {0}?', $setSong->id)]) ?>
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
