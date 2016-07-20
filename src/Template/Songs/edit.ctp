<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $song->id],
                ['confirm' => __('Are you sure you want to delete # {0}?', $song->id)]
            )
        ?></li>
        <li><?= $this->Html->link(__('List Songs'), ['action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('List Song Instances'), ['controller' => 'SongInstances', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Song Instance'), ['controller' => 'SongInstances', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Song Tags'), ['controller' => 'SongTags', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Song Tag'), ['controller' => 'SongTags', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="songs form large-9 medium-8 columns content">
    <?= $this->Form->create($song) ?>
    <fieldset>
        <legend><?= __('Edit Song') ?></legend>
        <?php
            echo $this->Form->input('title');
            echo $this->Form->input('written_by');
            echo $this->Form->input('performed_by');
            echo $this->Form->input('base_key');
            echo $this->Form->input('content', ['class' => 'sbk-lyrics-panel']);
            echo $this->Form->input('original_filename');
            echo $this->Form->input('meta_tags');
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
