<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Playlist'), ['action' => 'edit', $playlist->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Playlist'), ['action' => 'delete', $playlist->id], ['confirm' => __('Are you sure you want to delete # {0}?', $playlist->id)]) ?> </li>
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
            <td><?= $this->Number->format($playlist->performer_id) ?></td>
        </tr>
    </table>
    <div class="related">
        <h4><?= __('Sets') ?></h4>
        <?php if (!empty($playlist->playlist_sets)): ?>
        <table cellpadding="0" cellspacing="0">
            <tr>
                <th><?= __('Id') ?></th>
                <th><?= __('Set Title (Act)') ?></th>
                <?php /*<th><?= __('Playlist Id') ?></th> */ ?>
                <th><?= __('Order') ?></th>
                <th class="actions"><?= __('Actions') ?></th>
            </tr>
            <?php foreach ($playlist->playlist_sets as $playlistSets): ?>
            <tr>
                <td><?= h($playlistSets->id) ?></td>
                <td><?= h($playlistSets->set->title)?><?php 
                    if($playlistSets->set->performer->nickname !== '') {
                        echo ' ('.h($playlistSets->set->performer->nickname).')';
                    } else {
                        echo ' ('.h($playlistSets->set->performer->name).')';
                    } ?></td>
                <?php /*<td><?= h($playlistSets->playlist_id) ?></td> */ ?>
                <td><?= h($playlistSets->order) ?></td>
                <td class="actions">
                    [<?= $this->Html->link(__('Edit'), ['controller' => 'PlaylistSets', 'action' => 'edit', $playlistSets->id]) ?>]

                    [<?= $this->Form->postLink(__('Remove this set from the Playlist'), ['controller' => 'PlaylistSets', 'action' => 'delete', $playlistSets->id], ['confirm' => __('Are you sure you want to delete # {0}?', $playlistSets->id)]) ?>]

                </td>
            </tr>
            <tr>
                <td colspan="5">
                    <table cellpadding="0" cellspacing="0" class="set-songs">
                        <tr>
                            <th><?= __('Order') ?></th>
                            <th><?= __('title') ?></th>
                            <th class="actions"><?= __('Actions') ?></th>
                        </tr>
                        <?php foreach ($playlistSets->set->set_songs as $setSongs): ?>
                        <tr>
                            <td class="set-song-sort-order"><?= h($setSongs->order) ?></td>
                            <td class="set-song-title"><?= h($setSongs->song->title) . ' <span class="performed-by">(' .  $setSongs->song->performed_by . ')</span>'; ?></td>
                            <td class="actions">
                                [<?= $this->Form->postLink(__('remove from set'), ['controller' => 'SetSongs', 'action' => 'delete', $setSongs->id], ['confirm' => __('Are you sure you want to remove Set-Song relationship # {0}?', $setSongs->id)]) ?>]
                
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </table>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
    <?php endif; ?>
    </div>
</div>
