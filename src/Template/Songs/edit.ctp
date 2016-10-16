<nav class="large-3 medium-4 columns" id="actions-sidebar">
        <li><?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $song->id],
                ['confirm' => __('Are you sure you want to delete # {0}?', $song->id)]
            )
        ?>
    <?= $this->element('standard_menu') ?>
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
