<?php /* Template/Playlist/view.php */ ?>

<div class="playlists view large-10 medium-9 columns content">
    <span class="button float-right"><?= $this->Html->link(__('Edit Playlist'), ['action' => 'edit', $playlist->id]) ?></span>
    <span class="button float-right"><?= $this->Html->link(__('List Playlists'), ['controller' => 'Playlists', 'action' => 'index']) ?></span>
    <span class="search-form float-right"><?= $this->Form->create('search songs', ['url' => ['controller' => 'Songs', 'action' => 'index']]) ?>
        <fieldset><?= $this->Form->input('Search', ['label' => false]) ?></fieldset>
        <span class="button"><?= $this->Form->button(__('Search for Songs')) ?></span>
        <?= $this->Form->end() ?>
    </span>
    <h3><?= h($playlist->title) ?></h3>
    <?php
         if($playlist->performer->nickname !== '') {
                echo ' ('.h($playlist->performer->nickname).')';
            } else {
                echo ' ('.h($playlist->performer->name).')';
            }
     ?>
    <div class="related">
        <?php if (!empty($playlist->playlist_sets)): ?>
        <table cellpadding="0" cellspacing="0" class="sets">
            <tr>
                <th><?= __('Id') ?></th>
                <th><?= __('Set Title (Act)') ?></th>
                <?php /*<th><?= __('Playlist Id') ?></th> */ ?>
                <th><?= __('Order') ?></th>
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
            </tr>
            <tr>
                <td colspan="5">
                    <table cellpadding="0" cellspacing="0" class="set-songs">
                   
                        <?php foreach ($playlistSets->set->set_songs as $setSongs): ?>
                        <tr>
                            <td class="set-song-key set-song-capo"><?= h($setSongs->key) ?><span class="capo">(<?= h($setSongs->capo) ?>)</span></td>
                            <td class="set-song-title"><?= h($setSongs->song->title) ?><span class="performed-by">(<?= h($setSongs->song->performed_by) ?>)</span></td>
                            <td class="actions">
                                <span class="button view"><?= 
                                    $this->Html->link(__(
                                        'View'
                                    ), [
                                        'controller' => 'Songs', 
                                        'action' => 'view', 
                                        $setSongs->song->id
                                    ]) ?></span>  
                                <span class="button arrow move-up">&uparrow;</span>
                                <span class="button arrow move-down">&downarrow;</span>           
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
