<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Song Vote'), ['action' => 'edit', $songVote->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Song Vote'), ['action' => 'delete', $songVote->id], ['confirm' => __('Are you sure you want to delete # {0}?', $songVote->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Song Votes'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Song Vote'), ['action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Songs'), ['controller' => 'Songs', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Song'), ['controller' => 'Songs', 'action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="songVotes view large-9 medium-8 columns content">
    <h3><?= h($songVote->id) ?></h3>
    <table class="vertical-table">
        <tr>
            <th><?= __('Song') ?></th>
            <td><?= $songVote->has('song') ? $this->Html->link($songVote->song->title, ['controller' => 'Songs', 'action' => 'view', $songVote->song->id]) : '' ?></td>
        </tr>
        <tr>
            <th><?= __('Id') ?></th>
            <td><?= $this->Number->format($songVote->id) ?></td>
        </tr>
        <tr>
            <th><?= __('Timestamp') ?></th>
            <td><?= h($songVote->timestamp) ?></tr>
        </tr>
    </table>
</div>
