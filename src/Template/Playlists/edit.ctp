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
            'Sort Sets'
        ), [
            'controller' => 'Playlists', 
            'action' => 'sortSets', 
            $playlist->id
        ]) ?></span>
    <?= $this->Form->create($playlist) ?>
    <fieldset>
        <legend><?= __('Edit Playlist') ?></legend>
        <span class="playlist-title"><?= $this->Form->input('title', ['div'=>'playlist-title']) ?></span>
        <span class="playlist-performer-id"><?= $this->Form->input('performer_id') ?></span>
        <span class="playlist-edit-submit button"><?= $this->Form->button(__('Update Title & Act')) ?></span>
    </fieldset>
    <?= $this->Form->end() ?>
    <div class="related">
        <?php if (!empty($playlist->playlist_sets)): ?>
        <table cellpadding="0" cellspacing="0" class="sets">
            <?php foreach ($playlist->playlist_sets as $playlistSets): ?>
            <tr playliststeid="<?= $playlistSets->id; ?>"  setnumber="<?= $playlistSets->set->id; ?>" setorder="<?= $playlistSets->order; ?>">
                <td>Set #<?= h($playlistSets->id) ?></td>
                <td><?= h($playlistSets->set->title)?><?php 
                    if($playlistSets->set->performer->nickname !== '') {
                        echo ' ('.h($playlistSets->set->performer->nickname).')';
                    } else {
                        echo ' ('.h($playlistSets->set->performer->name).')';
                    } ?></td>
                <?php /*<td><?= h($playlistSets->playlist_id) ?></td> */ ?>
                <td class="playlist-sets-order"><?= h($playlistSets->order) ?></td>
                <td class="actions">
                    <span class="button"><?= 
                        $this->Form->postLink(__('X'), [
                            'controller' => 'PlaylistSets', 
                            'action' => 'deleteret', 
                            $playlistSets->id, 
                            'playlists', 'edit', $playlist->id
                        ], [
                            'confirm' => __(
                                'Are you sure you want to delete # {0}?', 
                                $playlistSets->id
                            )
                        ]) ?></span>
                </td>
            </tr>
            <tr>
                <td colspan="5" class="set-songs-container">
                    <table cellpadding="0" cellspacing="0" class="set-songs setSongs sortable" setnumber="<?= $playlistSets->set->id; ?>">
                        <?php foreach ($playlistSets->set->set_songs as $setSongs): ?>
                        
                        <tr>
                            <td class="set-song-key set-song-capo">
                                <?= $this->Form->create($setSong, ['url' => ['controller' => 'SetSongs', 'action' => 'editret', $setSongs->id, 'playlists', 'edit', $playlist->id]]) ?>
                                <fieldset>
                                <?= $this->Form->hidden('set_id', ['value' => $playlistSets->set->id]) ?>
                                <?= $this->Form->hidden('song_id', ['value' => $setSongs->song->id]) ?>
                                <span class="key"><?= $this->Form->input('key', ['label'=> false, 'value'=>h($setSongs->key)]) ?></span>
                                <span class="capo"><span>(</span><?= $this->Form->input('capo', ['label'=> false, 'value'=>h($setSongs->capo), 'type'=> 'text']) ?><span>)</span></span>
                            </td>
                            <td class="set-song-title"><?= h($setSongs->song->title) ?><span class="performed-by">(<?= h($setSongs->song->performed_by) ?>)</span></td>
                            <td class="set-song-details">
                                <span class="song-order" style="display: none;")><?= $setSongs->order ?><?= $this->Form->hidden('order', ['value'=>$setSongs->order]) ?></span>
                                <span class="performer-id"><?= $this->Form->select('performer_id', $performers, ['label'=> false,  'value'=>$setSongs->performer_id]) ?></span>
                            </td>
                            <td class="actions">
                                <span class="button"><?= $this->Form->button(__('Update')) ?></span>
                                <!-- span class="button"><?= 
                                    $this->Html->link(__(
                                        'Edit Set-Song'
                                    ), [
                                        'controller' => 'SetSongs', 
                                        'action' => 'editret', 
                                        $setSongs->id, 
                                        'playlists', 'edit', $playlist->id
                                    ]) ?>
                                </span -->
                                <span class="button"><?= 
                                    $this->Html->link(__(
                                        'Edit'
                                    ), [
                                        'controller' => 'Songs', 
                                        'action' => 'editret', 
                                        $setSongs->song->id, 
                                        'playlists', 'edit', $playlist->id
                                    ]) ?>
                                </span>
                                <!-- span class="button"><?= 
                                    $this->Html->link(__(
                                        'View'
                                    ), [
                                        'controller' => 'Songs', 
                                        'action' => 'view', 
                                        $setSongs->song->id
                                    ]) ?>
                                </span --> 
                                </fieldset>
                                <?= $this->Form->end() ?>    
                                <span class="button"><?= 
                                    $this->Form->postLink(__(  //Must be outside the form element!!!
                                        'X'
                                    ), [
                                        'controller' => 'SetSongs', 
                                        'action' => 'deleteret', 
                                        $setSongs->id, 
                                        'playlists', 'edit', $playlist->id
                                    ], [
                                        'confirm' => __(
                                            'Are you sure you want to remove "'.$setSongs->song->title.'" from set ['.$playlistSets->set->title.']?', 
                                            $setSongs->id
                                        )
                                    ]) ?>
                                </span>
                                <!-- span class="button move-up"><button>&uparrow;</button></span>
                                <span class="button move-down"><button>&downarrow;</button></span -->  
                                
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </table>
                        <div class="set-add-new-song-ui add-new-ui" add_text="Song">
                            <div class="add-song form">
                                <?= $this->Form->create($setSong, ['url' => ['controller' => 'SetSongs', 'action' => 'addret', 'playlists', 'edit', $playlist->id]]) ?>
                                <fieldset>
                                    <?php
                                        echo '<span class="playlist-song-id">'.$this->Form->input('song_id', ['label'=> '', 'empty' => 'Song Title ...', 'options' => $songs]).'</span>';
                                        echo '<span class="playlist-performer-id">'.$this->Form->input('performer_id', ['label'=> '', 'empty' => 'Performer ...', 'options' => $performers]).'</span>';
                                        echo '<span class="playlist-key">'.$this->Form->input('key', ['label'=> false, 'placeholder' => 'Key']).'</span>';
                                        echo '<span class="playlist-capo">'.$this->Form->input('capo', ['label'=> false, 'placeholder' => 'Capo', 'min' => 0, 'max' => 14]).'</span>';
                                        echo $this->Form->hidden('set_id', ['value' => $playlistSets->set->id]);
                                        echo $this->Form->hidden('order', ['value' => sizeof($playlistSets->set->set_songs) + 1]);
                                    ?>
                                </fieldset>                            
                                <span class="playlist-edit-set-song-add-submit button"><?= $this->Form->button(__('Add existing song')) ?></span>
                                <?= $this->Form->end() ?>
                            </div>
                            <div class="add-new-song form">
                                <?= $this->Form->create($song, ['url' => ['controller' => 'SetSongs', 'action' => 'addAndLinkSong', 'playlists', 'edit', $playlist->id]]) ?>
                                <fieldset>
                                    <?php
                                        echo '<span class="playlist-new-song-title">'.$this->Form->input('title', ['label'=> false, 'placeholder' => 'Song Title']).'</span>';
                                        echo '<span class="playlist-new-song-performed-by">'.$this->Form->input('performed_by', ['label'=> false, 'placeholder' => 'Who sang this song?']).'</span>';
                                        echo '<span class="playlist-performer-id">'.$this->Form->input('performer_id', ['label'=> '', 'empty' => 'Performer ...', 'options' => $performers]).'</span>';
                                        echo '<span class="playlist-key">'.$this->Form->input('key', ['label'=> false, 'placeholder' => 'Key']).'</span>';
                                        echo $this->Form->hidden('set_id', ['value' => $playlistSets->set->id]);
                                        echo $this->Form->hidden('order', ['value' => sizeof($playlistSets->set->set_songs) + 1]);
                                    ?>
                                </fieldset>
                                <span class="playlist-edit-set-song-add-submit button"><?= $this->Form->button(__('Create and add new Song')) ?></span>
                                <?= $this->Form->end() ?>
                        </div>
                    </div>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
    <?php endif; ?>
    </div>
    <div class="playlist-add-new-set-ui add-new-ui" add_text="Set">
        <div class="playlistSets form">
            <?= $this->Form->create($playlistSet, ['url' => ['controller' => 'PlaylistSets', 'action' => 'addret', 'playlists', 'edit', $playlist->id]]) ?>
            
            <fieldset>
                <?php
                    echo $this->Form->input('set_id', ['empty' => 'Please select ...', 'options' => $sets, 'label' => false]);
                    echo $this->Form->hidden('order', ['value' => sizeof($playlist->playlist_sets) + 1]);
                    echo $this->Form->hidden('playlist_id', ['value' => $playlist->id]);
                ?>
            </fieldset>
            <span class="playlist-edit-set-add-submit button"><?= $this->Form->button(__('Add Existing Set')) ?></span>
            <?= $this->Form->end() ?>
        </div>
        <div class="sets form ">
            <?= $this->Form->create($set, ['url' => ['controller' => 'PlaylistSets', 'action' => 'addAndLinkSet', 'playlistSets', 'playlists', 'edit', $playlist->id]]) ?>
            <fieldset>
                <?php
                    echo '<span class="inlinetop">'.$this->Form->input('title',['label'=> false, 'placeholder' => 'Set Title']).'</span>';
                    echo '<span class="inlinetop">'.$this->Form->input('performer_id', ['options' => $performers, 'value' => $playlist->performer_id, 'label'=> false, 'empty' => 'Performer ...']).'</span>';
                    //echo '<span class="inlinetop playlist-new-set-comment">'.$this->Form->input('comment',['label'=> false, 'placeholder' => 'Comment']).'</span>';
                    echo $this->Form->hidden('order', ['value' => sizeof($playlist->playlist_sets) + 1]);
                    echo $this->Form->hidden('playlist_id', ['value' => $playlist->id]);
                ?>
            </fieldset>
            <span class="playlist-edit-set-add-submit button"><?= $this->Form->button(__('Create and add New Set')) ?></span>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>