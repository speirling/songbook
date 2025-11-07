<?php /* Template/Viewer/custom.php*/  
//An interface for defining custom lists
//Accessed at https://fps.epeelo.com:9134/songbook/viewer/custom

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

function get_custom_url () {
	var url = '';

    jQuery.each(jQuery('#customlist li'), function(i, val){
    	url = url + 'c[]=' + jQuery(val).attr('data-id') + '&';
    });
    //get rid of the extra ampersand
	url = url.slice(0, -1);
	
    //console.log(url, jQuery(filter_form_fields));
    
    return url;
}

function set_filter_hidden_inputs () {
	var filter_form_fields = jQuery('#filter_container form fieldset'); 

	jQuery('input[name="f[]"]', filter_form_fields).remove();
	
	if(jQuery('#edit_form #id').val() > 0) {
		//there's an id, no need to pass the rest of the variables (unless you want to handle unsubmitted form??)
		//but in that case How do you know that the URL is the version of the list you want, not the record retrieved buy the ID?
		//It would be better to filter the main list without reloading the customlist. that would require reworking the way the left-pane filters work.
    	filter_form_fields.append('<input type="hidden" name="custom_id" value = "' + jQuery('#edit_form #id').val() + '">');
    } else {
        jQuery.each(jQuery('#customlist li'), function(i, val){
        	//if you pass "c" as the name of the array, then songlistComponent will filter the main songlist using PHP to match the custom list
        	//the behaviour needed in the customlist view is that the songlist should be filtered in an inverted way
        	// - by javascript - and the custom list should be displayed in the customlist pane
        	//so I'll use "f" as the name for the filtered array in this instance 
        	filter_form_fields.append('<input type="hidden" name="f[]" value = "' + jQuery(val).attr('data-id') + '">');
        });
        filter_form_fields.append('<input type="hidden" name="custom_title" value = "' + jQuery('#edit_form #title').val() + '">');
        filter_form_fields.append('<input type="hidden" name="custom_comment" value = "' + jQuery('#edit_form #comment').val() + '">');
        //filter_form_fields.append('<input type="hidden" name="custom_id" value = "' + jQuery('#edit_form #id').val() + '">');
    }
}

function set_customlists_form_url_element_to_match_custom_list() {
	var url = get_custom_url();

	jQuery('#edit_form #url').val(url);
}

function remove_customlist_elements_from_filtered_songlist() {
	jQuery.each(jQuery('#customlist li'), function(i, val){
    	jQuery('ul#songlist li[data-id="' + jQuery(val).attr('data-id') + '"').remove();
    });
}


$(document).ready(function(){

	/* put a filter textbox at the top of the standard songlist 
	   exactly as in standard songbook viewer */
	   
	   //filter the songlist whenever the songlist_filter changes
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
        
        //button to reinitialise the songlist_filter
        jQuery('span#clear_filter_button').on('click', function (event) {
            jQuery('#songlist_filter').val('');
            jQuery('#songlist > li').show();
        });
    
    
    /* show/hide the advanced filter panel 
	   exactly as in standard songbook viewer */
	   
        SBK.StaticFunctions.make_hideable_panel('#filter_container>.container-hideable', "...", "^");

	////////////////////////////////////////
	
	/* When you click on a song in either standard or custom list, 
	   the song should move to the other list ("targetlist")
	   AND the custom url should be refreshed */
	   
    jQuery('#songlist>li, #customlist>li').on('click', function (event) {

    	this_li = jQuery(event.target).closest('li');
    	//identify the target list
    	targetlist_id = (this_li.parent().attr('id') === 'songlist') ? 'customlist' : 'songlist';
    	
    	jQuery('ul#' + targetlist_id).append(this_li);
    	set_customlists_form_url_element_to_match_custom_list();
		set_filter_hidden_inputs();
    });
    
    
    /* add an action to the display-custom-url button, so that it opens the custom list as currently defined */
    jQuery('#display-custom-url').on('click', function() {
    	custom_url = get_custom_url();
    	title = jQuery('#edit_form #title').val();
    	comment = jQuery('#edit_form #comment').val();
    	custom_id = jQuery('#edit_form #id').val();
        window.open('https://fps.epeelo.com:9134/songbook/viewer?' + custom_url + '&custom_title=' + title + '&custom_comment =' + comment + '&custom_id=' + custom_id, '_blank')
    });
        
    /* add an action to the retrieve-custom-list button, so that it opens the generic custom list Interface */
    jQuery('#retrieve-custom-list').on('click', function() {
    	window.open('https://fps.epeelo.com:9134/songbook/customlists/', '_blank')
    });
    
    /* add an action to the return-to-songbook button, so that it opens the generic custom list Interface */
    jQuery('#return-to-songbook').on('click', function() {
    	window.location.assign('https://fps.epeelo.com:9134/songbook/viewer')
    });
    
    /////////////////////////////////////////////////
    //if the filter is used when the custom list has been started, then we've to re-create the custom list and remove from songlist
    // the definition of the current state of the customlist should have been passed using the "f" array
    // - see get_custom_url () above 
    // The list is recreated in PHP - see below - but the corresponding hidden filter inputs have to be set using javascript:  
	set_filter_hidden_inputs();
	//also, if a song is in the customlist, is should be removed from the filtered songlist (if it's there)
	remove_customlist_elements_from_filtered_songlist(); 



});
</script>

<div class="" id="viewer-sidebar">
    <span id="filter_container">
    		
    	<input id="songlist_filter" type="text" /> <span class="clear-filters button" id="clear_filter_button">X</span>

    	<?php /* Hideable filter panel --------------------------------- */ ?>
    	<span class="container-hideable"  style="visibility: hidden;">
        	<?= $this->Form->create(null, ['type' => 'get', 'url' => ['controller' => 'viewer', 'action' => 'custom']]) ?>
            <fieldset class="performer-tags-filter">
            
                <span class="clear-filters button" onclick="SBK.CakeUI.form.clear_filters(this)">X</span>
                <span class="performer-id tag-id">     
                	<h3>Performer</h3>                     
               		<?= $this->Form->control('performers[]', ['label' => '', 'empty' => 'Please select ...', 'options' => $performers, 'class'=>'exclude-from-select2', 'default' => $selected_performer]); ?>
                </span>
                <span class="tag-id">
                	<h3>Tags</h3>
                    <?= $this->Form->control('tags', ['label' => 'Include songs with these:', 'options' => $all_tags, 'multiple' => true, 'default' => $selected_tags]); ?>
                    <?= $this->Form->control('exclude_tag_id', ['label' => 'Exclude songs with any of these:', 'options' => $all_tags, 'multiple' => true, 'default' => $selected_exclude_tags]); ?>
                </span>
                <span class="selected-tags-and-performer button"><?= $this->Form->button(__('Filter the list')) ?></span>
                </span>
                
            </fieldset>
            <?= $this->Form->end() ?>
        	
        	
        </span>
        <?php /* end of Hideable filter panel --------------------------------- */ ?>
    </span>
<?php 
	echo $filtered_list;
?>

</div>
<div id="custom-list-display" class='filtered-songlist'>
<div class='button' id='display-custom-url'>Display the custom list URL</div>
<div class='button' id='retrieve-custom-list'>CustomList db UI</div>
<div class='button' id='return-to-songbook'>Back to Songbook</div>

    <div class="customlists form content">
<?php 
//$cl_data is defined in ViewerController.php
//test URL:https://fps.epeelo.com:9134/songbook/viewer/custom?f[0]=33&f[1]=1176&f[2]=38&f[3]=43&f[4]=45&custom_id=1&custom_title=test&custom_comment=blah

?>
			
            <?= $this->Form->create(null, 
                [
                    'id' => "edit_form", 
                    'url' => [
                        'controller' => "Customlists", 
                        'action' => $cl_data['action'] . "/" . $cl_data['id']
                    ]
                 ]) ?>
            <fieldset>
                <legend><?= __('Edit Customlist') ?></legend>
                <?php
                echo $this->Form->control('title', ['value' => $cl_data['title'], 'templates' => ['inputContainer' => '<div class="container-title">{{content}}</div>']]);
                echo $this->Form->control('comment', ['value' => $cl_data['comment'], 'templates' => ['inputContainer' => '<div class="container-comment">{{content}}</div>']]);
                echo $this->Form->control('url', ['value' => $cl_data['url'], 'templates' => ['inputContainer' => '<div class="container-url">{{content}}</div>']]);
                echo $this->Form->control('id', ['value' => $cl_data['id'],'templates' => ['inputContainer' => '<div class="container-id">{{content}}</div>']]);
                ?>
            </fieldset>
<ul id="customlist"><?php 
if(isset($custom_list_songs)) { 
    //This should prefill the current/selected custom list
    //but where is $custom_list_songs set?? -> set in ViewerController
    //the Editable custom list is set to include any songs specified by "f" in the URL
    //  e.g. f[]=1155&f[]=1172&f[]=1164&f[]=1143&f[]=750
    // Note, if the URL contains both "f" and "c", 
    // then the existing custom list ("f") will be displayed (f) in the edit pane, 
    // and only those songs specified by "c" will be available to select for adding to the custom list.
    // so you can create a new custom list from an existing custom list if you really want to
    $html = '';
    
    if($custom_list_songs == null) {
        $html = '';
    } else {
        foreach ($custom_list_songs as $song) {
            $primary_key = "";
            $primary_capo = "";
            $performers_html='';
            $existing_performer_keys = [];
            $url_text_for_edit_form = ''; // recreate thie url so that you can pass it to the edit form 
            
            $html = $html . '<li data-id="' . $song['id'] . '" data-key="' . $primary_key . '" data-capo="' . $primary_capo . '">';
            $html = $html . '<span class="song-title">' . $song['title'] . "</span>";
            $html = $html . $performers_html;
            $html = $html . '</li>';
            
            $url_text_for_edit_form = $url_text_for_edit_form . 'c=' . $song['id'] . '&';
        }
        $url_text_for_edit_form = rtrim($url_text_for_edit_form, '&'); //remove final ampersand
        
        if($custom_list_songs->count() == 0) {
            $html = '';
        }
    }
    echo $html;
} else {
    
}
?></ul>
            <?= $this->Form->button(__('Submit')) ?>
            <?= $this->Form->end() ?>

</div>
        
        
