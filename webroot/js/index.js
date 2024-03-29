/*global jQuery document */

jQuery(document).ready(function() {
	//special css for iPad
	if(navigator.userAgent.search(/ipad/i) > 0) {
	    jQuery('body').addClass('iPad');
	}

	//set chord editor if a song edit page is visible
	var lyrics_panels = jQuery('.sbk-lyrics-panel');
	if (lyrics_panels.length > 0) {
	    new SBK.ChordEditor(jQuery('.sbk-lyrics-panel')).render();
	}

	//improve any visible select boxes
	//console.log(jQuery('select'));
	jQuery('select:not(.exclude-from-select2)').select2();   //on the lyrics display key/capo box at least, you don't want select2 controlling dimensions etc.'
	
	// Dashboard Songlist -----------------------------
	//if there's a dashboard songlist, set it to respond to clicks
	jQuery('.playlists.view .set-songs tbody tr').on('click touch', SBK.CakeUI.select.clicked_row);
	jQuery('.dashboard.index tbody tr').on('click touch', SBK.CakeUI.select.clicked_row);
	jQuery('.songs.index tbody tr').on('click touch', SBK.CakeUI.select.clicked_row);
	jQuery('.events.view .related tbody tr').on('click touch', SBK.CakeUI.select.clicked_row);
	
	if(jQuery('.songs.index tbody tr').length === 1) {
		SBK.CakeUI.select.mark_row(jQuery('.songs.index tbody tr').first());
	}

	jQuery('div.dashboard').attr('tabindex', 0).focus().keypress(function (event) {
	    SBK.CakeUI.select.up_down_arrow_keypress(event);
        return false;
    });

	
	jQuery('<span class="button arrow move-up">&uparrow;</span>').appendTo('.song-row .actions')
	.on('click touch', function(event){
		event.stopPropagation();
        SBK.CakeUI.select.adjacent_row('up', jQuery(this));
     });

	jQuery('<span class="button arrow move-down">&downarrow;</span>').appendTo('.song-row .actions')
	.on('click touch', function(event){
		event.stopPropagation();
        SBK.CakeUI.select.adjacent_row('down', jQuery(this));
     });
     
     jQuery('tr.song-row:last  .actions.move-down').hide();
     jQuery('tr.song-row:first .actions.move-up').hide();

     jQuery('tr.song-row .multi-select').on('click touch', SBK.CakeUI.select.tag_multi_edit);
     //end Dashboard song list -----------------------


	 jQuery('#collapse_sets').on('click touch', SBK.CakeUI.collapse_sets);
	

     jQuery('.add-new-ui').each(function () {
         SBK.CakeUI.toggleable.make(this);
     });

     var DASHBOARD_TAG_TIMEOUT;
     jQuery(
             '#actions-sidebar .select2, ' +
             '#actions-sidebar .text-search input '
         ).click(function () {
             var self = jQuery(this);

             clearTimeout (DASHBOARD_TAG_TIMEOUT);
         });

     jQuery(
             '#actions-sidebar .performer-id select, ' +
             '#actions-sidebar .venue select, ' +
             '#actions-sidebar .tag-id select, ' +
             '#actions-sidebar .tag-id-exclude select '
         ).change(function () {
             var self = jQuery(this);

             clearTimeout (DASHBOARD_TAG_TIMEOUT);

             DASHBOARD_TAG_TIMEOUT = setTimeout( function () {
                 self.parents('form').submit();
             }, 5000);
         });

     jQuery('#actions-sidebar .text-search input').on('keypress', function () {
         SBK.CakeUI.filter_songlist(jQuery('.index>table'), jQuery(this).val());
     });
     jQuery('.selected-tags-and-performer.button').click(function(){
         clearTimeout (DASHBOARD_TAG_TIMEOUT);
     });

     // Make table filterable by columns ----------------------
     /* Any table can be made filterable by assigning it the class "filter-columns" - or by contining it within a container that has that class. */
     jQuery('.filter-columns').each(function () {
         var table = jQuery(this), count = 0, filter_strings = [], filter_callback;

         jQuery('th', table).each(function () {
             var th = jQuery(this), column = count, input = jQuery('<input type="text">').appendTo(th);
             
             input.keyup(function () {
                 filter_strings[column] = input.val();

                 filter_callback();
             });
             count = count + 1;
         });

         function filter_callback () {
             jQuery('tr', table).each(function () {
                 var tr = jQuery(this), count = 0, display_row = true, hide_row_class = 'hide-row';

                 jQuery('td', tr).each(function () {
                     var td = jQuery(this), search_string = filter_strings[count], text = td.text(), result;

                     if(typeof(search_string) !== 'undefined' && search_string !== '') {
                        result = text.search(new RegExp(search_string, "i"));
                        console.log(count, search_string, text, result);
                         if (result === -1) {
                             console.log('hide----------------');
                             display_row = false;
                         };
                     }

                     count = count + 1;
                 });

                 if(display_row) {
                     tr.removeClass(hide_row_class);
                 } else {
                     tr.addClass(hide_row_class);
                 } 
             });
         }
     })
});
