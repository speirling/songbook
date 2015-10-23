<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Playlist Set'), ['action' => 'edit', $playlistSet->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Playlist Set'), ['action' => 'delete', $playlistSet->id], ['confirm' => __('Are you sure you want to delete # {0}?', $playlistSet->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Playlist Sets'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Playlist Set'), ['action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Sets'), ['controller' => 'Sets', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Set'), ['controller' => 'Sets', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Playlists'), ['controller' => 'Playlists', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Playlist'), ['controller' => 'Playlists', 'action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="playlistSets view large-9 medium-8 columns content">
    <h3><?= h($playlistSet->id) ?></h3>
    <table class="vertical-table">
        <tr>
            <th><?= __('Set') ?></th>
            <td><?= $playlistSet->has('set') ? $this->Html->link($playlistSet->set->title, ['controller' => 'Sets', 'action' => 'view', $playlistSet->set->id]) : '' ?></td>
        </tr>
        <tr>
            <th><?= __('Playlist') ?></th>
            <td><?= $playlistSet->has('playlist') ? $this->Html->link($playlistSet->playlist->title, ['controller' => 'Playlists', 'action' => 'view', $playlistSet->playlist->id]) : '' ?></td>
        </tr>
        <tr>
            <th><?= __('Id') ?></th>
            <td><?= $this->Number->format($playlistSet->id) ?></td>
        </tr>
        <tr>
            <th><?= __('Order') ?></th>
            <td><?= $this->Number->format($playlistSet->order) ?></td>
        </tr>
    </table>
</div>
