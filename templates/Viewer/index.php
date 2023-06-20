<?php /* Template/Viewer/index.php*/  
    $filter_on = false;

    if ($this->getRequest()->is(array('post', 'put', 'get'))) {
        if ($this->getRequest()->is(array('get'))) {
            $query_parameters = $this->getRequest()->getQuery();
        } else {
            $query_parameters = $this->getRequest()->getData();
        }
        
        if (array_key_exists('performer_id', $query_parameters) && $query_parameters['performer_id']) {
            $filter_on = true;
            $selected_performer = $query_parameters['performer_id'];
        }
    }
?>
<script>
$(document).ready(function(){
	/* Make songnames into links, opening the song in the viewer panel */
    jQuery('#songlist>li').on('click', function (event) {
        this_li = jQuery(event.target);
        target_iframe = jQuery('#viewer-main>iframe');

		link_url = '/songbook/songs/embedded/' + this_li.attr('data-id') + '?vw=' + target_iframe.width() + '&vh=' + target_iframe.height() + '&key=' + this_li.attr('data-key') + '&capo=' + this_li.attr('data-capo') + '';
    	
    	console.log(link_url);
    	target_iframe.attr('src', link_url);
		
    });

	/* put a filter textbox at the top of the list */
    jQuery('input#songlist_filter').keyup(function(){

        var self = this, lis = jQuery('#songlist > li');
        var searchText = self.value.toUpperCase();

        var matching_lis = lis.filter(function(i, li){
            var li_text = jQuery(li).text().toUpperCase();
            return ~li_text.indexOf(searchText);
        });

        lis.hide();
        matching_lis.show();

    });

    jQuery('span#clear_filter_button').on('click', function (event) {
        jQuery('#songlist_filter').val('');
        jQuery('#songlist > li').show();
    });

    /* show/hide the advanced filter panel */
    SBK.StaticFunctions.make_hideable_panel('#filter_container>.container-hideable', "...", "^");
});
</script>

<div class="" id="viewer-sidebar">
    <span id="filter_container">
    		
    	<input id="songlist_filter" type="text" /> <span class="clear-filters button" id="clear_filter_button">X</span>

    	<?php /* Hideable filter panel --------------------------------- */ ?>
    	<span class="container-hideable"  style="visibility: hidden;">
    	<?= $this->Form->create($filtered_list, ['type' => 'get', 'url' => ['controller' => 'viewer', 'action' => 'index']]) ?>
        <fieldset class="performer-tags-filter">
        
            <span class="clear-filters button" onclick="SBK.CakeUI.form.clear_filters(this)">X</span>
            <span class="performer-id tag-id">     
            	<h3>Performer</h3>                     
           		<?= $this->Form->control('performer_id', ['label' => '', 'empty' => 'Please select ...', 'options' => $performers, 'default' => $selected_performer]); ?>
            </span>
            <span class="tag-id">
            	<h3>Tags</h3>
                <?= $this->Form->control('filter_tag_id', ['label' => 'Include songs with these:', 'options' => $all_tags, 'multiple' => true, 'default' => $selected_tags]); ?>
                <?= $this->Form->control('exclude_tag_id', ['label' => 'Exclude songs with any of these:', 'options' => $all_tags, 'multiple' => true, 'default' => $selected_exclude_tags]); ?>
            </span>
            
            <span class="selected-tags-and-performer button"><?= $this->Form->button(__('Filter the list')) ?></span>
            </span>
        </fieldset>
        <?= $this->Form->end() ?>
    	<a class="button" href = "/songbook/dashboard">Dashboard</a>
    	<ul>
            <li><?= $this->Html->link(__('E-AMU'), ['controller' => 'viewer', 'action' => 'index', '?'=>['text_search'=>'', 'performer_id'=>'1', 'filter_tag_id'=>[15]]], ['target'=>'_blank']) ?></li>
            <li><?= $this->Html->link(__('M-AMU'), ['controller' => 'viewer', 'action' => 'index', '?'=>['text_search'=>'', 'performer_id'=>'3', 'filter_tag_id'=>[15]]], ['target'=>'_blank']) ?></li>
            <li><?= $this->Html->link(__('E-Irish'), ['controller' => 'viewer', 'action' => 'index', '?'=>['text_search'=>'', 'performer_id'=>'1', 'filter_tag_id'=>[2]]], ['target'=>'_blank']) ?></li>
            <li><?= $this->Html->link(__('E-Lively-AMU'), ['controller' => 'viewer', 'action' => 'index', '?'=>['text_search'=>'', 'performer_id'=>'1', 'filter_tag_id'=>[13, 15]]], ['target'=>'_blank']) ?></li>
            <li><?= $this->Html->link(__('Christmas-AMU'), ['controller' => 'viewer', 'action' => 'index', '?'=>['text_search'=>'', 'filter_tag_id'=>[15, 21]]], ['target'=>'_blank']) ?></li>
            <li><?= $this->Html->link(__('Piano'), ['controller' => 'viewer', 'action' => 'index', '?'=>['text_search'=>'', 'filter_tag_id'=>[1]]], ['target'=>'_blank']) ?></li>
        </ul>
        </span>
        <?php /* end of Hideable filter panel --------------------------------- */ ?>
    </span>



    <ul id="songlist">
    <?php
    foreach ($filtered_list as $song){
        $primary_key = $song['base_key'];
        //debug($song);
        /* $song: id, title, written_by, performed_by, base_key, content */
        
        
        if(sizeof($song['set_songs']) > 0) {
            $primary_key = $song['set_songs'][0]['key'];
            $primary_capo = $song['set_songs'][0]['capo'];
        } else {
            $primary_key = false;
            $primary_capo = false;
        }
        $performers_html='';
        $existing_performer_keys = [];
        foreach ($song['set_songs'] as $set_song) {
            $performer_key = $set_song['performer']['nickname'].$set_song['key'];
            if (!in_array($performer_key, $existing_performer_keys)) {
                array_push($existing_performer_keys, $performer_key);
                if($filter_on == false || $selected_performer == $set_song['performer']['id']) {
                    $performers_html = $performers_html . '<span class="performer short-form">';
                    $performers_html = $performers_html . '<span class="nickname">' . strtolower(substr($set_song['performer']['nickname'], 0, 1)) . '</span>';
                    $performers_html = $performers_html . '<span class="key">' . $set_song['key'] . '</span>';
                    $performers_html = $performers_html . '</span>';
                    
                    $primary_key = $set_song['key'];
                    $primary_capo = $set_song['capo'];
                }
            }
        } 
        echo('<li data-id="' . $song['id'] . '" data-key="' . $primary_key . '" data-capo="' . $primary_capo . '">');
        echo($song['title']);
        echo($performers_html);
        echo('</li>');
    }
    ?>
    </ul>
    
</div>

<div id="viewer-main" >
    <iframe src="">   
    
    </iframe>
</div>
        
        
