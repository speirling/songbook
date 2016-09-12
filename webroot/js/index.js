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
			SBK.playlist_sort.set_order_value(ui.item, ui.item.prev(), ui.item.next());
		}
	});
	

	 
	SBK.playlist_sort = {
			move_set_song: function (direction, button) {
		        var current_row, previous_row, row_before_previous_row, next_row, row_after_next_row, preceding_row, following_row;
		       
		       current_row = button.closest('tr');
               previous_row = current_row.prev().prev();
               row_before_previous_row = previous_row.prev().prev();
               next_row = current_row.next().next();
               row_after_next_row = next_row.next().next();
		       if(direction === 'up') {
		    	   preceding_row = row_before_previous_row;
		    	   following_row = previous_row;
		       } else if(direction === 'down') {
		    	   preceding_row = next_row;
		    	   following_row = row_after_next_row;
		       } else {
		           return;
		       }
		       SBK.playlist_sort.set_order_value(current_row, preceding_row, following_row);
		    },
	

			set_order_value: function (current_row, previous_row, next_row) {
		        var order_0, order_1, new_sort_order;
		      
		       if(isNaN(jQuery('.playlist-song-order input', previous_row).val())) {
		    	   order_0 = 0;
		       } else {
		    	   order_0 = parseFloat(jQuery('.playlist-song-order input', previous_row).val());
		       }
		       if(isNaN(jQuery('.playlist-song-order input', next_row).val())) {
		    	   order_1 = 1000000;
		       } else {
		    	   order_1 = parseFloat(jQuery('.playlist-song-order input', next_row).val());
		       }
		       new_sort_order = order_0 + (order_1 - order_0)/2;
		       jQuery('.playlist-song-order input', current_row).val(new_sort_order);
		       console.log(current_row, previous_row, jQuery('.playlist-song-order input', previous_row).val(), order_0, next_row, jQuery('.playlist-song-order input', next_row).val(), order_1, new_sort_order);
		       current_form = jQuery('form', current_row);
		       console.log(current_form);
		       current_form.submit();
		    }
		}; 



    jQuery('.playlist-edit-form .sets .setSongs .move-up').click(function(){
        SBK.playlist_sort.move_set_song('up', jQuery(this));
     });
     
     jQuery('.playlist-edit-form .sets .setSongs .move-down').click(function(){
        SBK.playlist_sort.move_set_song('down', jQuery(this));
     });
     jQuery('.playlist-edit-form .sets .setSongs tr:last  .move-down').hide();
     jQuery('.playlist-edit-form .sets .setSongs tr:first  .move-up').hide();
});
