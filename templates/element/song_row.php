<?php
  /*
  The calling view must set the following variables:
  $return_point = [controller, method, id]
  $current_song = [id, title, written_by, performed_by]
  $this_set_songs = distinct array of [performer ([name, nickname]), key, capo]
  $set_song_object =  a setSong object - required to set up the key form
  $performers_list = list of all available performers for drop-down list in key form.
  $tags = list of all avilable tags
  */
?>
<tr class="song-row<?php if($current_song->played) {echo ' played';} ?>" id="song_id_<?= h($current_song->id) ?>">
    <td class="song-id"><?= h($current_song->id) ?></td>
    <td class="multi-select"><input type="checkbox" name="song_multiselect" value="<?= h($current_song->id) ?>"></td>
    <td class="performers">
        <?php 
        if(sizeof($this_set_songs) > 0) {
            $primary_key = $this_set_songs[0]['key'];
        } else {
            $primary_key = false;
        }
        $existing_performer_keys = [];
        foreach ($this_set_songs as $set_song) {
            $performer_key = $set_song['performer']['nickname'].$set_song['key'];
            if (!in_array($performer_key, $existing_performer_keys)) {
                array_push($existing_performer_keys, $performer_key);
                echo '<span class="performer">';
                echo '<span class="nickname">'.$set_song['performer']['nickname'].'</span>';
                echo '<span class="key">'.$set_song['key'].'</span>';
                echo '<span class="capo">'.$set_song['capo'].'</span>';
                echo '</span>';
            }
        } ?>
    </td>
    <td class="song-main"><span class="song-title"><?= h($current_song->title) ?></span> 
    <?php if ($current_song->performed_by === '') {
            echo '<span class="written-by">('.$current_song->written_by.')</span>';
        } else {
            echo '<span class="performed-by">('.$current_song->performed_by.')</span>';
    } ?>
    <span class="tags">
    <?php 
    if($current_song->song_tags) {
        $list_of_tags = '';
        foreach ($current_song->song_tags as $this_tag) {
            $list_of_tags = $list_of_tags . '<span class="tag">' . $this_tag->tag->title . '</span>';
        }
        echo $list_of_tags;
    }
    ?>
    </span>
    <span class="actions">
        <?= $this->element('ajax-button-form-view', ['current_song' => $current_song, 'primary_key' => $primary_key]); ?>
        <?= $this->element('ajax-button-form-played', ['current_song' => $current_song]); ?>
        <?= $this->element('ajax-button-form-vote', ['current_song' => $current_song]); ?>
        <?php
        if(isset($performers_list)) { ?>
        <span class="key-form">
            <?= $this->Form->create(Null, ['url' => ['controller' => 'SetSongs', 'action' => 'addAjax']]) ?>
            <fieldset>
                <?php
                    echo $this->Form->hidden('set_id', ['value' => 0]);
                    echo $this->Form->hidden('song_id', ['value' => $current_song->id]);
                    echo $this->Form->control('performer_id', ['empty' => 'Please select ...', 'options' => $performers_list]);
                    echo $this->Form->control('key');
                    //echo $this->Form->control('capo');
                ?>
            <span class="button"><?= $this->Form->button(__('Add Key'), ['type' => 'button', 'onclick' => 'SBK.CakeUI.form.ajaxify(this, SBK.CakeUI.ajaxcallback.song_row.add_key);']) ?></span>
            </fieldset>
            <?= $this->Form->end() ?>
        </span>
        <?php
        }

        if(isset($all_tags)) {
            echo $this->element('ajax-form-tags', [
                'current_song' => $current_song,
                'all_tags' => $all_tags,
            	'ajax_callback' => 'SBK.CakeUI.ajaxcallback.song_row.set_tags'
            ]);
        }
        ?>
        <span class="set-song-edit">
        <?php
        if(sizeof($this_set_songs) > 0) {
            $primary_key = $this_set_songs[0]['key'];
        } else {
            $primary_key = false;
        }
        $existing_performer_keys = [];
        foreach ($this_set_songs as $set_song) {
                echo '<span class="set-song">';
                echo '<span class="nickname">'.$set_song['performer']['nickname'].'</span>';
                echo '<span class="key">'.$set_song['key'].'</span>';
                echo '<span class="capo">'.$set_song['capo'].'</span>';
                echo '<span class="set-song-edit-button button">' . $this->Html->link(__('Edit'), ['controller' => 'setSongs', 'action' => 'edit', $set_song->id]) . '</span>'; 
                echo '</span>';
        } ?>
        </span>
    </span>
    </td>
</tr>