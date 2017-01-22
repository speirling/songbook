<?php /* Template/Dashboard/index.ctp */  
    $controller_name = 'Dashboard';
    echo($this->element('standard_menu') );
?>
<div class="dashboard index large-9 medium-9 columns content">

    <h3><?= __('Homepage') ?></h3>
    <?= $this->Form->create($filtered_list, ['url' => ['controller' => 'dashboard', 'action' => 'index']]) ?>
        <fieldset>
        	<span class="text-search">
        	<?php echo $this->Form->input('text_search', ['label'=>'Text Search (name only)', 'default' => $search_string]); ?>
        	</span>
	        <span class="performer-id">                          
	        <?php echo $this->Form->input('performer_id', ['empty' => 'Please select ...', 'options' => $performers]); ?>
	        </span>
    		<?php echo $this->Form->hidden('Search', ['value' => $search_string]);  ?>
        	<span class="tag-id"><label for="tag-id">Tags</label>
            <?php
                echo $this->Form->input('tag_id', ['label' => '', 'empty' => 'Tag ...', 'options' => $all_tags, 'multiple' => true, 'default' => $selected_tags]);
            ?>
        	</span>
	        <span class="venue">
	        <?php echo $this->Form->input('venue', ['empty' => 'Please select ...', 'options' => $venues]); ?>
	        </span>
        	<span class="selected-tags-and-performer button"><?= $this->Form->button(__('Filter the list')) ?></span>
        </fieldset>
        
    <?= $this->Form->end() ?>
    
    <table cellpadding="0" cellspacing="0">
        
        <tbody>
            <?php 
            foreach ($filtered_list as $song){ 
            //echo debug($song->set_songs);
	            if ($search_string == "") {
	                $return_port_id = " ";
	            } else {
	                $return_port_id = $search_string;
	            }
                echo $this->element('song_row', [ 
                    'return_point' => ['controller'=>'dashboard', 'method'=>'index', 'id'=>$return_port_id],
                    'current_song' => $song,
                    //'this_set_songs' => [$song->_matchingData['SetSongs']],
                    'this_set_songs' => $song->set_songs,
                	'set_song_object' => $setSong,
                	'performers_list' => $performers
                ]); 
             } ?>  
        </tbody>
    </table>
    <div class="paginator">
        <ul class="pagination">
            <?= $this->Paginator->prev('< ' . __('previous')) ?>
            <?= $this->Paginator->numbers() ?>
            <?= $this->Paginator->next(__('next') . ' >') ?>
        </ul>
        <p><?= $this->Paginator->counter() ?></p>
    </div>
</div>
