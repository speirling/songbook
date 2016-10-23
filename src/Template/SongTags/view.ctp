<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Song Tag'), ['action' => 'edit', $songTag->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Song Tag'), ['action' => 'delete', $songTag->id], ['confirm' => __('Are you sure you want to delete # {0}?', $songTag->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Song Tags'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Song Tag'), ['action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Songs'), ['controller' => 'Songs', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Song'), ['controller' => 'Songs', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Tags'), ['controller' => 'Tags', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Tag'), ['controller' => 'Tags', 'action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="songTags view large-9 medium-8 columns content">
    <h3><?= h($songTag->id) ?></h3>
    <table class="vertical-table">
        <tr>
            <th><?= __('Song') ?></th>
            <td><?= $songTag->has('song') ? $this->Html->link($songTag->song->title, ['controller' => 'Songs', 'action' => 'view', $songTag->song->id]) : '' ?></td>
        </tr>
        <tr>
            <th><?= __('Tag') ?></th>
            <td><?= $songTag->has('tag') ? $this->Html->link($songTag->tag->title, ['controller' => 'Tags', 'action' => 'view', $songTag->tag->id]) : '' ?></td>
        </tr>
        <tr>
            <th><?= __('Id') ?></th>
            <td><?= $this->Number->format($songTag->id) ?></td>
        </tr>
    </table>
</div>
