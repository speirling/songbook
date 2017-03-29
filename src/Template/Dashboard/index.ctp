<?php /* Template/Dashboard/index.ctp */  
    $controller_name = 'Dashboard';
?>

<nav class="large-2 medium-2 columns" id="actions-sidebar">
<?= $this->Form->create($filtered_list, ['url' => ['controller' => 'dashboard', 'action' => 'index']]) ?>
    <fieldset>
        <span class="text-search">
        <?= $this->Form->input('text_search', ['label'=>'Text Search (name only)', 'default' => $search_string]); ?>
        </span>
        <span class="performer-id">                          
        <?= $this->Form->input('performer_id', ['empty' => 'Please select ...', 'options' => $performers]); ?>
        </span>
        <span class="tag-id"><label for="tag-id">Tags</label>
        <?= $this->Form->input('filter_tag_id', ['label' => '', 'options' => $all_tags, 'multiple' => true, 'default' => $selected_tags]); ?>
        </span>
        <span class="venue">
        <?= $this->Form->input('venue', ['empty' => 'Please select ...', 'options' => $venues]); ?>
        </span>
        <span class="selected-tags-and-performer button"><?= $this->Form->button(__('Filter the list')) ?></span>
        <span class="clear-filters button"><button type="button" onclick="SBK.CakeUI.form.clear_filters(this)">X</button></span>
    </fieldset>
    <?= $this->Form->end() ?>

    <ul class="side-nav">
        <li><?= $this->Html->link(__('New Song'), ['controller' => 'Songs', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('New Event'), ['controller' => 'Events', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('Songs'), ['controller' => 'Songs', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('Playlists'), ['controller' => 'Playlists', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('Performers'), ['controller' => 'Performers', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('Events'), ['controller' => 'Events', 'action' => 'index']) ?></li>
    </ul>
</nav>

<div class="print_title"><?php
	$print_title = '';
	if ($search_string) {
		$print_title = $print_title . $search_string;
		$print_title = $print_title . " | ";
	}
	foreach($performers as $performer_id => $performer_title) {
		if($performer_id == $selected_performer && $performer_id != 0) {
			$print_title = $print_title . $performer_title;
			$print_title = $print_title . " | ";
		}
	}
	foreach($venues as $venue_id => $venue_title) {
		if($venue_id == $selected_venue) {
			$print_title = $print_title . $venue_title;
			$print_title = $print_title . " | ";
		}
	}
	foreach($all_tags as $tag_id => $tag_title) {
		if(in_array($tag_id, $selected_tags)) {
			$print_title = $print_title . $tag_title;
			$print_title = $print_title . ", ";
		}
	}

	echo substr($print_title, 0, -2);
?></div>
<div class="dashboard index large-10 medium-10 columns content">   
	<div id="tag_multieditor" style="display: none;">
		<span class="tag-form">
		    <?= $this->Form->create(null, ['url' => ['controller' => 'SongTags', 'action' => 'AddTagMultiAjax']]);  ?>
		    <fieldset>
		        <?php
		        echo '<span class="tag-id">'.$this->Form->input('tag_id', ['label' => '', 'style'=>'width: 80%', 'options' => $all_tags, 'multiple' => true, 'default' => $selected_tags]).'</span>';
		        ?>
            <span class="song_id_input_holder"></span>
		    </fieldset>
		    <span class="tag-add-submit button">
		        <?php echo $this->Form->button(__('Tag <br> selected <br> songs'), ['type' => 'button', 'onclick' => 'SBK.CakeUI.form.ajaxify(this, SBK.CakeUI.ajaxcallback.multitag);']); ?>
		    </span>
		    <?= $this->Form->end() ?>
		</span>
	</div>
    <table cellpadding="0" cellspacing="0">
        <tbody>
            <?php 
            foreach ($filtered_list as $song){
                if ($search_string == "") {
                    $return_port_id = " ";
                } else {
                    $return_port_id = $search_string;
                }
                echo $this->element('song_row', [
                    'current_song' => $song,
                    'this_set_songs' => $song->set_songs,
                    'performers_list' => $performers
                ]); 
             } ?>  
        </tbody>
    </table>
    <?php if(!$filter_on) { ?>
    <div class="paginator">
        <ul class="pagination">
            <?= $this->Paginator->prev('< ' . __('previous')) ?>
            <?= $this->Paginator->numbers() ?>
            <?= $this->Paginator->next(__('next') . ' >') ?>
        </ul>
        <p><?= $this->Paginator->counter() ?></p>
    </div>
    <?php } ?>
</div>
