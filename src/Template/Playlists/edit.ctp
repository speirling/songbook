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
    <?= $this->Form->create($playlist) ?>
    <fieldset>
        <legend><?= __('Edit Playlist') ?></legend>
        <span class="playlist-title"><?= $this->Form->input('title', ['div'=>'playlist-title']) ?></span>
        <span class="playlist-performer-id"><?= $this->Form->input('performer_id') ?></span>
    </fieldset>
    <span class="playlist-edit-submit button"><?= $this->Form->button(__('Submit')) ?></span>
    <?= $this->Form->end() ?>
    <div class="related">
        <?php if (!empty($playlist->playlist_sets)): ?>
        <table cellpadding="0" cellspacing="0" class="sets">
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
                <td colspan="5">
                    <table cellpadding="0" cellspacing="0" class="set-songs setSongs">
                        <?php foreach ($playlistSets->set->set_songs as $setSongs): ?>
                        <?= $this->Form->create($setSong, ['url' => ['controller' => 'SetSongs', 'action' => 'editret', $setSongs->id, 'playlists', 'edit', $playlist->id]]) ?>
                        <fieldset>
                        <?= $this->Form->hidden('set_id', ['value' => $playlistSets->set->id]) ?>
                        <?= $this->Form->hidden('song_id', ['value' => $setSongs->song->id]) ?>
                        <span class="playlist-song-order" style="display: none;")><?= $setSongs->order ?><?= $this->Form->hidden('order', ['value'=>$setSongs->order]) ?></span>
                        <tr>
                            <td class="set-song-key set-song-capo">
                                <span class="playlist-key"><?= $this->Form->input('key', ['label'=> false, 'value'=>h($setSongs->key)]) ?></span>
                                <span class="playlist-capo"><?= $this->Form->input('capo', ['label'=> false, 'value'=>h($setSongs->capo)]) ?></span>
                            </td>
                            <td class="set-song-title"><?= h($setSongs->song->title) ?><span class="performed-by">(<?= h($setSongs->song->performed_by) ?>)</span></td>
                            <td class="set-song-details">
                                <span class="playlist-performer-id"><?= $this->Form->select('performer_id', $performers, ['label'=> false,  'value'=>$setSongs->performer_id]) ?></span>
                            </td>
                            <td class="actions">
                                <span class="button"><button><?= $this->Form->button(__('Update')) ?></span>
                                <!-- span class="button"><?= 
                                    $this->Html->link(__(
                                        'Edit Set-Song'
                                    ), [
                                        'controller' => 'SetSongs', 
                                        'action' => 'editret', 
                                        $setSongs->id, 
                                        'playlists', 'edit', $playlist->id
                                    ]) ?></button></span -->   
                                    
                                <span class="button"><button><?= 
                                    $this->Html->link(__(
                                        'View'
                                    ), [
                                        'controller' => 'Songs', 
                                        'action' => 'view', 
                                        $setSongs->song->id
                                    ]) ?></button></span>     
                                <span class="button"><button><?= 
                                    $this->Form->postLink(__(
                                        'X'
                                    ), [
                                        'controller' => 'SetSongs', 
                                        'action' => 'deleteret', 
                                        $setSongs->id, 
                                        'playlists', 'edit', $playlist->id
                                    ], [
                                        'confirm' => __(
                                            'Are you sure you want to remove Set-Song relationship # {0}?', 
                                            $setSongs->id
                                        )
                                    ]) ?></button></span>
                                    <span class="button move-up"><button>&uparrow;</button></span>
                                    <span class="button move-down"><button>&downarrow;</button></span>  
                            </td>
                        </tr>
                        </fieldset>
                        <?= $this->Form->end() ?>
                        <?php endforeach; ?>
                    </table>
                    
                    <div class="add-song form">
                        <?= $this->Form->create($setSong, ['url' => ['controller' => 'SetSongs', 'action' => 'addret', 'playlists', 'edit', $playlist->id]]) ?>
                        <fieldset>
                            <?php
                                echo '<span class="playlist-song-id">'.$this->Form->input('song_id', ['label'=> '', 'empty' => 'Song Title ...', 'options' => $songs]).'</span>';
                                echo '<span class="playlist-performer-id">'.$this->Form->input('performer_id', ['label'=> '', 'empty' => 'Performer ...', 'options' => $performers]).'</span>';
                                echo '<span class="playlist-key">'.$this->Form->input('key', ['label'=> false, 'placeholder' => 'Key']).'</span>';
                                echo '<span class="playlist-capo">'.$this->Form->input('capo', ['label'=> false, 'placeholder' => 'Capo', 'min' => 0, 'max' => 14]).'</span>';
                                echo $this->Form->hidden('set_id', ['value' => $playlistSets->set->id]);
                                echo $this->Form->hidden('order', ['value' => sizeof($playlistSets->set->set_songs)]);
                            ?>
                        <span class="playlist-edit-set-song-add-submit button"><?= $this->Form->button(__('Add song')) ?></span>
                        </fieldset>
                        <?= $this->Form->end() ?>
                    </div>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
    <?php endif; ?>
    </div>
</div>