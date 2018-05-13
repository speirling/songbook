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
    <td class="performers">
        <?php 
        $existing_performer_keys = [];
        foreach ($this_set_songs as $set_song) {
            $performer_key = $set_song['performer']['nickname'].$set_song['key'];
            if (!in_array($performer_key, $existing_performer_keys)) {
                array_push($existing_performer_keys, $performer_key);
                echo ''.$set_song['performer']['nickname'].'';
                echo ' ';
            }
        } ?>
    </td>
    <td class="keys">
        <?php 
        $existing_performer_keys = [];
        foreach ($this_set_songs as $set_song) {
            $performer_key = $set_song['performer']['nickname'].$set_song['key'];
            if (!in_array($performer_key, $existing_performer_keys)) {
                array_push($existing_performer_keys, $performer_key);
                echo ''.$set_song['key'].'';
                if ($set_song['capo'] > 0) {
                    echo ''.$set_song['capo'].'';
                }
                echo ' ';
            }
        } ?>
    </td>
    <td class="song-main"><span class="song-title"><?= h($current_song->title) ?></span> </td>
    <td class="song-main"><?php if ($current_song->performed_by === '') {
            echo '<span class="written-by">('.$current_song->written_by.')</span>';
        } else {
            echo '<span class="performed-by">('.$current_song->performed_by.')</span>';
    } ?>
    
    </td>
</tr>