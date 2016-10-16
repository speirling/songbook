<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('New Song Vote'), ['action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Songs'), ['controller' => 'Songs', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Song'), ['controller' => 'Songs', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="songVotes index large-9 medium-8 columns content">
    <h3><?= __('Song Votes') ?></h3>
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
            <?php foreach ($songVotes as $songVote): ?>
            <tr>
                <td><?= $this->Number->format($songVote->id) ?></td>
                <td><?= $songVote->has('song') ? $this->Html->link($songVote->song->title, ['controller' => 'Songs', 'action' => 'view', $songVote->song->id]) : '' ?></td>
                <td><?= h($songVote->timestamp) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['action' => 'view', $songVote->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['action' => 'edit', $songVote->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $songVote->id], ['confirm' => __('Are you sure you want to delete # {0}?', $songVote->id)]) ?>
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
