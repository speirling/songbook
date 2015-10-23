<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Song Instance'), ['action' => 'edit', $songInstance->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Song Instance'), ['action' => 'delete', $songInstance->id], ['confirm' => __('Are you sure you want to delete # {0}?', $songInstance->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Song Instances'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Song Instance'), ['action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Songs'), ['controller' => 'Songs', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Song'), ['controller' => 'Songs', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Performers'), ['controller' => 'Performers', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Performer'), ['controller' => 'Performers', 'action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="songInstances view large-9 medium-8 columns content">
    <h3><?= h($songInstance->id) ?></h3>
    <table class="vertical-table">
        <tr>
            <th><?= __('Song') ?></th>
            <td><?= $songInstance->has('song') ? $this->Html->link($songInstance->song->title, ['controller' => 'Songs', 'action' => 'view', $songInstance->song->id]) : '' ?></td>
        </tr>
        <tr>
            <th><?= __('Performer') ?></th>
            <td><?= $songInstance->has('performer') ? $this->Html->link($songInstance->performer->name, ['controller' => 'Performers', 'action' => 'view', $songInstance->performer->id]) : '' ?></td>
        </tr>
        <tr>
            <th><?= __('Key') ?></th>
            <td><?= h($songInstance->key) ?></td>
        </tr>
        <tr>
            <th><?= __('Id') ?></th>
            <td><?= $this->Number->format($songInstance->id) ?></td>
        </tr>
    </table>
</div>
