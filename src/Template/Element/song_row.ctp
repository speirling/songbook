<?php
  /*
  The calling view must set the following variables:
  $return_point = [controller, method, id]
  $current_song = [id, title, written_by, performed_by]
  $this_set_songs = distinct array of [performer ([name, nickname]), key, capo]
  $setSong =  a setSong object
  */
?>
<tr class="song-row">
    <td class="song-id"><?= h($current_song->id) ?></td>
    <td class="performers">
        <?php 
        if(sizeof($this_set_songs) > 0) {
            $primary_key = $this_set_songs[0]['key'];
        } else {
            $primary_key = false;
        }
        foreach ($this_set_songs as $set_song) {
            echo '<span class="performer">';
            echo '<span class="nickname">'.$set_song['performer']['nickname'].'</span>';
            echo '<span class="key">'.$set_song['key'].'</span>';
            echo '<span class="capo">'.$set_song['capo'].'</span>'; 
            echo '</span>';
        } ?>
    </td>
    <td class="song-main"><span class="song-title"><?= h($current_song->title) ?></span> 
    <?php if ($current_song->performed_by === '') {
            echo '<span class="written-by">('.$current_song->written_by.')</span>';
        } else {
            echo '<span class="performed-by">('.$current_song->performed_by.')</span>';
    } ?>
    <span class="actions">
        <span class="button view"><?= $this->Html->link(__('View'), ['controller'=>'Songs', 'action' => 'view', $current_song->id.'?key='.$primary_key], ['target'=>'_blank']) ?></span>
        <span class="button edit"><?= $this->Html->link(__('Edit'), ['action' => 'edit', $current_song->id], ['target'=>'_blank']) ?></span>
        <span class="button vote"><?= 
            $this->Html->link(__(
                'vote'
            ), [
                'controller' => 'SongVotes', 
                'action' => 'addret', 
                $current_song->id,
                $return_point['controller'], 
                $return_point['method'], 
                $return_point['id']
            ]) ?>
        </span>  
        <span class="button performance"><?= 
            $this->Html->link(__(
                'played'
            ), [
                'controller' => 'SongPerformances', 
                'action' => 'addret',  
                $current_song->id,
                $return_point['controller'], 
                $return_point['method'], 
                $return_point['id']
            ]) ?>
        </span>
        <?php
        if(isset($performers_list)) { ?>
	    <span class="key-form">
	            <?= $this->Form->create($set_song_object, ['url' => ['controller' => 'SetSongs', 'action' => 'addret', $return_point['controller'], $return_point['method'], $return_point['id']]]) ?>
			    <fieldset>
			        <label><?= __('add key:') ?></label>
			        <?php
			            echo $this->Form->hidden('set_id', ['value' => 0]);
			            echo $this->Form->hidden('song_id', ['value' => $current_song->id]);
			            echo $this->Form->input('performer_id', ['empty' => 'Please select ...', 'options' => $performers_list]);
			            echo $this->Form->input('key');
			            //echo $this->Form->input('capo');
			        ?>
			    <span class="button"><?= $this->Form->button(__('Submit')) ?></span>
			    </fieldset>
			    <?= $this->Form->end() ?>
		</span>
        <?php
        }
        ?>
    </span>
    </td>
</tr>