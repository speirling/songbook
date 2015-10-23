<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $playlistSet->id],
                ['confirm' => __('Are you sure you want to delete # {0}?', $playlistSet->id)]
            )
        ?></li>
        <li><?= $this->Html->link(__('List Playlist Sets'), ['action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('List Sets'), ['controller' => 'Sets', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Set'), ['controller' => 'Sets', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Playlists'), ['controller' => 'Playlists', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Playlist'), ['controller' => 'Playlists', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="playlistSets form large-9 medium-8 columns content">
    <?= $this->Form->create($playlistSet) ?>
    <fieldset>
        <legend><?= __('Edit Playlist Set') ?></legend>
        <?php
            echo $this->Form->input('set_id', ['options' => $sets]);
            echo $this->Form->input('playlist_id', ['options' => $playlists]);
            echo $this->Form->input('order');
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
