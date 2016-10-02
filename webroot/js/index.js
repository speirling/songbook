/*global jQuery document */

jQuery(document).ready(function() {
	var lyrics_panels = jQuery('.sbk-lyrics-panel');

	if (lyrics_panels.length > 0) {
	    new SBK.ChordEditor(jQuery('.sbk-lyrics-panel')).render();
	}

	jQuery('select').select2();

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
	jQuery('#collapse_sets').on('click touch', SBK.CakeUI.collapse_sets);


    jQuery('.playlist-edit-form .sets .setSongs .move-up').on('click touch', function(){
        SBK.CakeUI.playlist_sort.move_set_song('up', jQuery(this));
     });
     
     jQuery('.playlist-edit-form .sets .setSongs .move-down').on('click touch', function(){
        SBK.CakeUI.playlist_sort.move_set_song('down', jQuery(this));
     });
     jQuery('.playlist-edit-form .sets .setSongs tr:last  .move-down').hide();
     jQuery('.playlist-edit-form .sets .setSongs tr:first  .move-up').hide();
     


     jQuery('.playlists.view .set-songs .move-up').on('click touch', function(event){
		event.stopPropagation();
         SBK.CakeUI.select.adjacent_row('up', jQuery(this));
      });
      
      jQuery('.playlists.view .set-songs .move-down').on('click touch', function(event){
		event.stopPropagation();
         SBK.CakeUI.select.adjacent_row('down', jQuery(this));
      });
});
