<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Performer'), ['action' => 'edit', $performer->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Performer'), ['action' => 'delete', $performer->id], ['confirm' => __('Are you sure you want to delete # {0}?', $performer->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Performers'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Performer'), ['action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="performers view large-9 medium-8 columns content">
    <h3><?= h($performer->name) ?></h3>
    <table class="vertical-table">
        <tr>
            <th><?= __('Name') ?></th>
            <td><?= h($performer->name) ?></td>
        </tr>
        <tr>
            <th><?= __('Nickname') ?></th>
            <td><?= h($performer->nickname) ?></td>
        </tr>
        <tr>
            <th><?= __('Id') ?></th>
            <td><?= $this->Number->format($performer->id) ?></td>
        </tr>
    </table>
</div>
