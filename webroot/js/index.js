/*global jQuery document */

jQuery(document).ready(function() {
	//special css for iPad
	if(navigator.userAgent.search(/ipad/i) > 0) {
	    jQuery('body').addClass('iPad');
	}

	var lyrics_panels = jQuery('.sbk-lyrics-panel');
	if (lyrics_panels.length > 0) {
	    new SBK.ChordEditor(jQuery('.sbk-lyrics-panel')).render();
	}

	jQuery('select').select2();
			/* replace these with the above single select2 assignment
			jQuery('select').not('.song-tags select').not('.tag-form select').select2(); //Intended for the left hand panel of the dashboard (not song lyrics page) - any select that's not tags'
			jQuery('.song-tags select').select2({
				  tags: true,
				  tokenSeparators: [',', ' '],
				  width: 'element'
				});
			jQuery('.tag-form select').width('100%').select2({
				  tags: true,
				  width: 'element'
				});
				
				//*/

 //Sortable tables depends on jQueryUI, with seems abandoned so I've removed it 20230616
/*
	jQuery('table.sortable tbody tr').prepend('<td class="handle">');
	jQuery('table.sortable.set-songs tbody').sortable({
		handle: '.handle',
		stop: function (event, ui) {
			SBK.CakeUI.playlist_sort.set_order_value(ui.item, ui.item.prev(), ui.item.next());
		},
		connectWith: 'table.sortable.set-songs tbody'
	});
	jQuery('table.sortable.sets tbody').sortable({
		handle: '.handle',
		stop: function (event, ui) {
			SBK.CakeUI.sets_sort.set_order_value(ui.item, ui.item.prev(), ui.item.next());
		},
		connectWith: 'table.sortable.sets tbody'
	});
*/
	jQuery('.playlists.view .set-songs tbody tr').on('click touch', SBK.CakeUI.select.clicked_row);
	jQuery('.dashboard.index tbody tr').on('click touch', SBK.CakeUI.select.clicked_row);
	jQuery('.songs.index tbody tr').on('click touch', SBK.CakeUI.select.clicked_row);
	jQuery('.events.view .related tbody tr').on('click touch', SBK.CakeUI.select.clicked_row);
	if(jQuery('.songs.index tbody tr').length === 1) {
		SBK.CakeUI.select.mark_row(jQuery('.songs.index tbody tr').first());
	}

	jQuery('#collapse_sets').on('click touch', SBK.CakeUI.collapse_sets);

	// Songlist -----------------------------
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
     //end song list -----------------------

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
