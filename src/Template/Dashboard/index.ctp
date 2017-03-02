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
        <?= $this->Form->input('tag_id', ['label' => '', 'options' => $all_tags, 'multiple' => true, 'default' => $selected_tags]); ?>
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



<div class="dashboard index large-10 medium-10 columns content">   
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
