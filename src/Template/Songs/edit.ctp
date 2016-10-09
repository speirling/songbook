<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $song->id],
                ['confirm' => __('Are you sure you want to delete # {0}?', $song->id)]
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
<div class="songs songs-edit form large-9 medium-8 columns content">
    <?= $this->Form->create($song) ?>
    <fieldset>
    <?= '<span class="button vote float-right">'.$this->Form->button(__('Save Song')).'</span>' ?>
        <legend><?= __('Edit Song') ?></legend>
        <?php
            echo '<span class="song-input-title">'.$this->Form->input('title').'</span>';
            echo '<span class="song-input-written_by">'.$this->Form->input('written_by').'</span>';
            echo '<span class="song-input-performed_by">'.$this->Form->input('performed_by').'</span>';
            echo '<span class="song-input-base_key">'.$this->Form->input('base_key').'</span>';
            echo '<span class="song-input-tags">'.$this->Form->input('meta_tags').'</span>';
            echo '<span class="song-input-content">'.$this->Form->input('content', ['class' => 'sbk-lyrics-panel']).'</span>';
        ?>
    </fieldset>
    <?= $this->Form->end() ?>
</div>
