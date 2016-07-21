<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Playlist'), ['action' => 'edit', $playlist->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Playlist'), ['action' => 'delete', $playlist->id], ['confirm' => __('Are you sure you want to delete # {0}?', $playlist->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Playlists'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Playlist'), ['action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Playlist Sets'), ['controller' => 'PlaylistSets', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Playlist Set'), ['controller' => 'PlaylistSets', 'action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="playlists view large-9 medium-8 columns content">
    <h3><?= h($playlist->title) ?></h3>
    <table class="vertical-table">
        <tr>
            <th><?= __('Title') ?></th>
            <td><?= h($playlist->title) ?></td>
        </tr>
        <tr>
            <th><?= __('Id') ?></th>
            <td><?= $this->Number->format($playlist->id) ?></td>
        </tr>
        <tr>
            <th><?= __('Act Id') ?></th>
            <td><?= $this->Number->format($playlist->act_id) ?></td>
        </tr>
    </table>
    <div class="related">
        <h4><?= __('Related Playlist Sets') ?></h4>
        <?php if (!empty($playlist->playlist_sets)): ?>
        <table cellpadding="0" cellspacing="0">
            <tr>
                <th><?= __('Id') ?></th>
                <th><?= __('Set Id') ?></th>
                <th><?= __('Playlist Id') ?></th>
                <th><?= __('Order') ?></th>
                <th class="actions"><?= __('Actions') ?></th>
            </tr>
            <?php foreach ($playlist->playlist_sets as $playlistSets): ?>
            <tr>
                <td><?= h($playlistSets->id) ?></td>
                <td><?= h($playlistSets->set_id) ?></td>
                <td><?= h($playlistSets->playlist_id) ?></td>
                <td><?= h($playlistSets->order) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['controller' => 'PlaylistSets', 'action' => 'view', $playlistSets->id]) ?>

                    <?= $this->Html->link(__('Edit'), ['controller' => 'PlaylistSets', 'action' => 'edit', $playlistSets->id]) ?>

                    <?= $this->Form->postLink(__('Delete'), ['controller' => 'PlaylistSets', 'action' => 'delete', $playlistSets->id], ['confirm' => __('Are you sure you want to delete # {0}?', $playlistSets->id)]) ?>

                </td>
            </tr>
            <?php endforeach; ?>
        </table>
    <?php endif; ?>
    </div>
</div>
