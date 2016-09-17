/*global jQuery document */

jQuery(document).ready(function() {
	var lyrics_panels = jQuery('.sbk-lyrics-panel');

	if (lyrics_panels.length > 0) {
	    new SBK.ChordEditor(jQuery('.sbk-lyrics-panel')).render();
	}

	jQuery('select').select2();

	jQuery('table.sortable tbody tr').prepend('<td class="handle">');
	jQuery('table.sortable tbody').sortable({
		handle: '.handle',
		stop: function (event, ui) {
			SBK.CakeUI.playlist_sort.set_order_value(ui.item, ui.item.prev(), ui.item.next());
		}
	});
	
	jQuery('.playlists.view .set-songs tbody tr').click(SBK.CakeUI.select.clicked_row);


    jQuery('.playlist-edit-form .sets .setSongs .move-up').click(function(){
        SBK.CakeUI.playlist_sort.move_set_song('up', jQuery(this));
     });
     
     jQuery('.playlist-edit-form .sets .setSongs .move-down').click(function(){
        SBK.CakeUI.playlist_sort.move_set_song('down', jQuery(this));
     });
     jQuery('.playlist-edit-form .sets .setSongs tr:last  .move-down').hide();
     jQuery('.playlist-edit-form .sets .setSongs tr:first  .move-up').hide();
     


     jQuery('.playlists.view .set-songs .move-up').click(function(event){
		event.stopPropagation();
         SBK.CakeUI.select.adjacent_row('up', jQuery(this));
      });
      
      jQuery('.playlists.view .set-songs .move-down').click(function(event){
		event.stopPropagation();
         SBK.CakeUI.select.adjacent_row('down', jQuery(this));
      });
});
