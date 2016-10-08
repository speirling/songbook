<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Song Performance'), ['action' => 'edit', $songPerformance->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Song Performance'), ['action' => 'delete', $songPerformance->id], ['confirm' => __('Are you sure you want to delete # {0}?', $songPerformance->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Song Performances'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Song Performance'), ['action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Songs'), ['controller' => 'Songs', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Song'), ['controller' => 'Songs', 'action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="songPerformances view large-9 medium-8 columns content">
    <h3><?= h($songPerformance->id) ?></h3>
    <table class="vertical-table">
        <tr>
            <th><?= __('Song') ?></th>
            <td><?= $songPerformance->has('song') ? $this->Html->link($songPerformance->song->title, ['controller' => 'Songs', 'action' => 'view', $songPerformance->song->id]) : '' ?></td>
        </tr>
        <tr>
            <th><?= __('Id') ?></th>
            <td><?= $this->Number->format($songPerformance->id) ?></td>
        </tr>
        <tr>
            <th><?= __('Timestamp') ?></th>
            <td><?= h($songPerformance->timestamp) ?></tr>
        </tr>
    </table>
</div>
