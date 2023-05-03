<?php
  /*
  The calling view must set the following variables:
  $current_song = [id, title, written_by, performed_by]
  $all_tags = list of all avilable tags
  */
?>

<span class="tag-form">
    <?php
    $selected_tags = [];
    foreach ($current_song->song_tags as $this_tag) {
        array_push($selected_tags, $this_tag['tag_id']);
    }
    echo $this->Form->create(null, ['url' => ['controller' => 'SongTags', 'action' => 'matchListAjax']]);
    ?>
    <fieldset>
        <?php
        echo '<span class="tag-id">'.$this->Form->control('tag_id', ['label' => '', 'options' => $all_tags, 'multiple' => true, 'default' => $selected_tags]).'</span>';
        echo $this->Form->hidden('song_id', ['value' => $current_song->id]);
        ?>
    </fieldset>
    <span class="tag-add-submit button">
        <?php echo $this->Form->button(__('Update tags'), ['type' => 'button', 'onclick' => 'SBK.CakeUI.form.ajaxify(this, SBK.CakeUI.ajaxcallback.song_row.set_tags);']); ?>
    </span>
    <?= $this->Form->end() ?>
</span>