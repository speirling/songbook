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
$(document).ready(function(){
	/* Make songnames into links, adding the song in the custom list panel */
    jQuery('#songlist>li').on('click', function (event) {

    	this_li = jQuery(event.target).closest('li');
    	
    	targetlist_id = (this_li.parent().attr('id') === 'songlist') ? 'customlist' : 'songlist';
    	
    	jQuery('ul#' + targetlist_id).append(this_li);
		
    });
    jQuery('#display-custom-url').on('click', function() {
    	var url= '';
        jQuery.each(jQuery('#customlist li'), function(i, val){
        	url = url + 'custom[]=' + jQuery(val).attr('data-id') + '&';
        });
        console.log(url);
        window.open('https://fps.epeelo.com:9134/songbook/viewer?' + url, '_blank')
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
                <span class="selected-custom">
                	<?= $this->Form->control('customselect', ['type' => 'textarea']); ?>
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
<ul id="customlist"></ul>
</div>
        
        
