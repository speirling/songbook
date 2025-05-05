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
    jQuery.each(jQuery('#customlist li'), function(i, val){
    	//if you pass "c" as the name of the array, then songlistComponent will filter the main songlist using PHP to match the custom list
    	//the behaviour needed in the customlist view is that the songlist should be filtered in an inverted way
    	// - by javascript - and the custom list should be displaued in the customlist pane
    	//so I'll use "f" as the name for the filtered array in this instance 
    	filter_form_fields.append('<input type="hidden" name="f[]" value = "' + jQuery(val).attr('data-id') + '">');
    });
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
		set_filter_hidden_inputs();
    });
    
    
    /* add an action to the display-custom-url button, so that it opens the custom list as currently defined */
    jQuery('#display-custom-url').on('click', function() {
    	custom_url = get_custom_url();
        window.open('https://fps.epeelo.com:9134/songbook/viewer?' + custom_url, '_blank')
    });
    
    /////////////////////////////////////////////////
    //if the filter is used when the custom list has been started, then we've to re-create the custom list and remove from songlit
    // the definition of the current state of the customlist should have been passed using the "f" array
    // - see get_custom_url () above 
    // The list is recreated in PHP - see below - but thecorresponding hidden filter inputs have to be set using javascript:  
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
<ul id="customlist"><?php 
if(isset($custom_list)) {
    $html = '';
    foreach ($custom_list as $song) {
        $primary_key = "";
        $primary_capo = "";
        $performers_html='';
        $existing_performer_keys = [];
        
        $html = $html . '<li data-id="' . $song['id'] . '" data-key="' . $primary_key . '" data-capo="' . $primary_capo . '">';
        $html = $html . '<span class="song-title">' . $song['title'] . "</span>";
        $html = $html . $performers_html;
        $html = $html . '</li>';
    }
    
    if($custom_list->count() == 0) {
        $html = '';
    }
    echo $html;
}
?></ul>
</div>
        
        
