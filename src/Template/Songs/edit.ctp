<?php /* Template/Songs/edit.php */ 
    $controller_name = 'Song';
    echo($this->element('standard_menu', ['controller_name' => $controller_name, 'delete_id' => $song->id]) );
?>

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
            echo '<span class="song-input-content">'.$this->Form->input('content', ['class' => 'sbk-lyrics-panel']).'</span>';
        ?>
    </fieldset>
    <?= $this->Form->end() ?>
    <?php 
        echo $this->element('ajax-form-tags', [
            'current_song' => $song,
            'all_tags' => $song->tags,
            'ajax_callback' => 'SBK.CakeUI.ajaxcallback.song_view.set_tags'
        ]);
    ?>
    
</div>
