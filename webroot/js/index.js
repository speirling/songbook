/*global jQuery document */

jQuery(document).ready(function() {
	var lyrics_panels = jQuery('.sbk-lyrics-panel');

	if (lyrics_panels.length > 0) {
	    new SBK.ChordEditor(jQuery('.sbk-lyrics-panel')).render();
	}

	jQuery('select').not('.song-tags select').not('.tag-form select').select2();
	jQuery('.song-tags select').select2({
		  tags: true,
		  tokenSeparators: [',', ' ']
		});
	jQuery('.tag-form select').width('100%').select2({
		  tags: true
		});
	

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

	jQuery('.playlists.view .set-songs tbody tr').on('click touch', SBK.CakeUI.select.clicked_row);
	jQuery('.dashboard.index tbody tr').on('click touch', SBK.CakeUI.select.clicked_row);
	jQuery('.songs.index tbody tr').on('click touch', SBK.CakeUI.select.clicked_row);
	jQuery('.events.view .related tbody tr').on('click touch', SBK.CakeUI.select.clicked_row);
	if(jQuery('.songs.index tbody tr').length === 1) {
		SBK.CakeUI.select.mark_row(jQuery('.songs.index tbody tr').first());
	}

	jQuery('#collapse_sets').on('click touch', SBK.CakeUI.collapse_sets);


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

     jQuery('.add-new-ui').each(function () {
         SBK.CakeUI.toggleable.make(this);
     });

     jQuery(
         '#actions-sidebar .performer-id select, ' +
         '#actions-sidebar .venue select'
     ).change(function () {
    	 jQuery(this).parents('form').submit();
     });

     var DASHBOARD_TAG_TIMEOUT;
     jQuery(
             '#actions-sidebar .tag-id select'
         ).change(function () {
             var self = jQuery(this);

             clearTimeout (DASHBOARD_TAG_TIMEOUT);
             DASHBOARD_TAG_TIMEOUT = setTimeout( function () {
                 self.parents('form').submit();
             }, 1000);
         })
});
