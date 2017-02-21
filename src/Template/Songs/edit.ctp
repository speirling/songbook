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
    <div class="song-tags">
        <?php 
            $selected_tags = [];
            foreach ($song->song_tags as $this_tag) {
                array_push($selected_tags, $this_tag['tag_id']);
            }
        ?>
        <?= $this->Form->create($songTag, ['url' => ['controller' => 'SongTags', 'action' => 'match_list', 'songs', 'edit', $song->id]]) ?>
        <fieldset>
            <?php
                echo '<span class="tag-id">'.$this->Form->input('tag_id', ['label' => '', 'empty' => 'Tag ...', 'options' => $tags, 'multiple' => true, 'default' => $selected_tags]).'</span>';
                echo $this->Form->hidden('song_id', ['value' => $song->id]);
            ?>
        </fieldset>                            
        <span class="tag-add-submit button"><?= $this->Form->button(__('Update tags')) ?></span>
        <?= $this->Form->end() ?> 
    </div>
    
</div>
