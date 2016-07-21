<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $playlist->id],
                ['confirm' => __('Are you sure you want to delete # {0}?', $playlist->id)]
            )
        ?></li>
        <li><?= $this->Html->link(__('List Playlists'), ['action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('List Playlist Sets'), ['controller' => 'PlaylistSets', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Playlist Set'), ['controller' => 'PlaylistSets', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="playlists form large-9 medium-8 columns content">
    <?= $this->Form->create($playlist) ?>
    <fieldset>
        <legend><?= __('Edit Playlist') ?></legend>
        <?php
            echo $this->Form->input('title');
            echo $this->Form->input('act_id');
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
