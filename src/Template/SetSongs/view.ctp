<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Set Song'), ['action' => 'edit', $setSong->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Set Song'), ['action' => 'delete', $setSong->id], ['confirm' => __('Are you sure you want to delete # {0}?', $setSong->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Songs'), ['controller' => 'Songs', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Song'), ['controller' => 'Songs', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Playlists'), ['controller' => 'Playlists', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Playlist'), ['controller' => 'Playlists', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Sets'), ['controller' => 'Sets', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Set'), ['controller' => 'Sets', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Set Songs'), ['controller' => 'SetSongs', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Set Song'), ['controller' => 'SetSongs', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Performers'), ['controller' => 'Performers', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Performer'), ['controller' => 'Performers', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="setSongs view large-9 medium-8 columns content">
    <h3><?= h($setSong->id) ?></h3>
    <table class="vertical-table">
        <tr>
            <th><?= __('Set') ?></th>
            <td><?= $setSong->has('set') ? $this->Html->link($setSong->set->title, ['controller' => 'Sets', 'action' => 'view', $setSong->set->id]) : '' ?></td>
        </tr>
        <tr>
            <th><?= __('Song') ?></th>
            <td><?= $setSong->has('song') ? $this->Html->link($setSong->song->title, ['controller' => 'Songs', 'action' => 'view', $setSong->song->id]) : '' ?></td>
        </tr>
        <tr>
            <th><?= __('Performer') ?></th>
            <td><?= $setSong->has('performer') ? $this->Html->link($setSong->performer->name, ['controller' => 'Performers', 'action' => 'view', $setSong->performer->id]) : '' ?></td>
        </tr>
        <tr>
            <th><?= __('Key') ?></th>
            <td><?= h($setSong->key) ?></td>
        </tr>
        <tr>
            <th><?= __('Id') ?></th>
            <td><?= $this->Number->format($setSong->id) ?></td>
        </tr>
        <tr>
            <th><?= __('Order') ?></th>
            <td><?= $this->Number->format($setSong->order) ?></td>
        </tr>
        <tr>
            <th><?= __('Capo') ?></th>
            <td><?= $this->Number->format($setSong->capo) ?></td>
        </tr>
    </table>
</div>
