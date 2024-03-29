<?php /* Template/Playlist/view.ctp */
    $controller_name = 'Playlist';
    echo($this->element('standard_menu', ['controller_name' => $controller_name]) );
?>
<div class="playlists view large-10 medium-9 columns content">
    <span class="button float-right"><?= $this->Html->link(__('Edit Playlist'), ['action' => 'edit', $playlist->id]) ?></span>
    <span class="button float-right"><?= $this->Html->link(__('Print all songs in Playlist'), ['action' => 'printable', $playlist->id]) ?></span>
    <span class="button float-right"><?= $this->Html->link(__('List Playlists'), ['controller' => 'Playlists', 'action' => 'index']) ?></span>
    <span class="button float-right"><?= $this->Html->link(__('Add new song'), ['controller' => 'Songs', 'action' => 'add']) ?></span>
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
                   
                        <?php foreach ($playlistSets->set->set_songs as $setSong) {
                            $this_set_songs = [[
                                'performer' => ['name' => $setSong->song->performed_by, 'nickname' => $setSong->song->performed_by], 
                                'key' => $setSong->key, 
                                'capo' => $setSong->capo
                                ]];
                            echo $this->element('song_row', [ 
                                'return_point' => ['controller'=>'playlists', 'method'=>'view', 'id'=>$playlist->id],
                                'current_song' => $setSong->song,
                                'this_set_songs' => [$setSong],
                				'set_song_object' => $setSong
                                ]); 
                            
                         } ?>
                    </table>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
    <?php endif; ?>
    </div>
</div>
