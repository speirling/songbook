<?php /* Template/Playlist/edit.php */ ?>

<nav class="large-2 medium-3 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $playlist->id],
                ['confirm' => __('Are you sure you want to delete # {0}?', $playlist->id)]
            )
        ?></li>
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
<div class="playlists playlist-edit-form form large-10 medium-9 columns content">
    <span class="button float-right"><?= 
        $this->Html->link(__(
            'View Playlist'
        ), [
            'controller' => 'Playlists', 
            'action' => 'view', 
            $playlist->id
        ]) ?></span>
    <span class="button float-right"><?= 
        $this->Html->link(__(
            'Edit Playlist'
        ), [
            'controller' => 'Playlists', 
            'action' => 'edit', 
            $playlist->id
        ]) ?></span>
        <legend><?= __('Sort sets within Playlist') ?></legend>
        <span class="playlist-title"><?= $playlist->title ?></span>
        <span class="playlist-performer-id"><?= $playlist->performer->name ?></span>
    <div class="related">
        <?php if (!empty($playlist->playlist_sets)): ?>
        <table cellpadding="0" cellspacing="0" class="sets sortable">
            <?php foreach ($playlist->playlist_sets as $playlistSets): ?>
            <tr setnumber="<?= $playlistSets->set->id; ?>">
                <td>Set #<?= h($playlistSets->id) ?></td>
                <td><?= h($playlistSets->set->title)?><?php 
                    if($playlistSets->set->performer->nickname !== '') {
                        echo ' ('.h($playlistSets->set->performer->nickname).')';
                    } else {
                        echo ' ('.h($playlistSets->set->performer->name).')';
                    } ?></td>
                <?php /*<td><?= h($playlistSets->playlist_id) ?></td> */ ?>
                <td class="playlist-sets-order"><?= h($playlistSets->order) ?>
                    <?= $this->Form->create($playlistSet, ['url' => ['controller' => 'PlaylistSets', 'action' => 'editret', $playlistSets->id, 'playlists', 'sortSets', $playlist->id]]) ?>
                        <fieldset>
                            <?php
                                echo $this->Form->hidden('set_id', ['value' => $playlistSets->set->id]);
                                echo $this->Form->hidden('playlist_id', ['value' => $playlist->id]);
                                echo $this->Form->hidden('order', ['value' => $playlistSets->order]);
                            ?>
                        </fieldset>
                    <?= $this->Form->end() ?>
                </td>
                <td class="actions">
                    <span class="button"><?= 
                        $this->Form->postLink(__('X'), [
                            'controller' => 'PlaylistSets', 
                            'action' => 'deleteret', 
                            $playlistSets->id, 
                            'playlists', 'sortSets', $playlist->id
                        ], [
                            'confirm' => __(
                                'Are you sure you want to delete # {0}?', 
                                $playlistSets->id
                            )
                        ]) ?></span>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
    <?php endif; ?>
    </div>
</div>