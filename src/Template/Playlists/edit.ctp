<nav class="large-3 medium-4 columns" id="actions-sidebar">
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
<div class="playlists form large-9 medium-8 columns content">
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
    <span class="playlist-edit-submit"><?= $this->Form->button(__('Submit')) ?></span>
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
                        $this->Html->link(__('Edit'), [
                            'controller' => 'Sets', 
                            'action' => 'editret', 
                            $playlistSets->set->id, 
                            'playlists', 'edit', $playlist->id
                        ]) ?></span>
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
                        <tr>
                            <td class="set-song-key"><?= h($setSongs->key) ?></td>
                            <td class="set-song-capo">(<?= h($setSongs->capo) ?>)</td>
                            <td class="set-song-title"><?= h($setSongs->song->title) ?><span class="performed-by">(<?= h($setSongs->song->performed_by) ?>)</span></td>
                            <td>
                            
                            <?= $this->Form->create($setSong, ['url' => ['controller' => 'SetSongs', 'action' => 'editret', $setSongs->id, 'playlists', 'edit', $playlist->id]]) ?>
                                <fieldset>
                                    <?= $this->Form->hidden('set_id', ['value' => $playlistSets->set->id]) ?>
                                    <?= $this->Form->hidden('song_id', ['value' => $setSongs->song->id]) ?>
                                    <span class="playlist-song-order"><?= $this->Form->input('order', ['label'=> false, 'placeholder' => 'Sort',  'value'=>$setSongs->order]) ?></span>
                                    <span class="playlist-performer-id"><?= $this->Form->select('performer_id', $performers, ['label'=> false,  'value'=>$setSongs->performer_id]) ?></span>
                                    <span class="playlist-key"><?= $this->Form->input('key', ['label'=> false, 'value'=>h($setSongs->key)]) ?></span>
                                    <span class="playlist-capo"><?= $this->Form->input('capo', ['label'=> false, 'value'=>h($setSongs->capo)]) ?></span>
                                    <span class="button"><?= $this->Form->button(__('Update')) ?></span>
                                </fieldset>
                            <?= $this->Form->end() ?>
                            
                            </td>
                            <td class="actions">
                                <!-- span class="button"><?= 
                                    $this->Html->link(__(
                                        'Edit Set-Song'
                                    ), [
                                        'controller' => 'SetSongs', 
                                        'action' => 'editret', 
                                        $setSongs->id, 
                                        'playlists', 'edit', $playlist->id
                                    ]) ?></span -->     
                                <span class="button"><?= 
                                    $this->Html->link(__(
                                        'View'
                                    ), [
                                        'controller' => 'Songs', 
                                        'action' => 'view', 
                                        $setSongs->song->id
                                    ]) ?></span>     
                                <span class="button"><?= 
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
                                    ]) ?></span>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </table>
                    
                    <div class="add-song form">
                        <?= $this->Form->create($setSong, ['url' => ['controller' => 'SetSongs', 'action' => 'addret', 'playlists', 'edit', $playlist->id]]) ?>
                        <fieldset>
                            <span class=""><?= __('Add a song:') ?></span>
                            <?php
                                echo '<span class="playlist-song-id">'.$this->Form->input('song_id', ['label'=> '', 'empty' => 'Song Title ...', 'options' => $songs]).'</span>';
                                echo '<span class="playlist-performer-id">'.$this->Form->input('performer_id', ['label'=> '', 'empty' => 'Performer ...', 'options' => $performers]).'</span>';
                                echo '<span class="playlist-key">'.$this->Form->input('key', ['label'=> false, 'placeholder' => 'Key']).'</span>';
                                echo '<span class="playlist-capo">'.$this->Form->input('capo', ['label'=> false, 'placeholder' => 'Capo', 'min' => 0, 'max' => 14]).'</span>';
                                echo $this->Form->hidden('set_id', ['value' => $playlistSets->set->id]);
                                echo $this->Form->hidden('order', ['value' => sizeof($playlistSets->set->set_songs)]);
                            ?>
                        <span class="playlist-edit-set-song-add-submit"><?= $this->Form->button(__('Submit')) ?></span>
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