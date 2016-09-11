/*global jQuery document */

jQuery(document).ready(function() {
	var lyrics_panels = jQuery('.sbk-lyrics-panel');

	if (lyrics_panels.length > 0) {
	    new SBK.ChordEditor(jQuery('.sbk-lyrics-panel')).render();
	}
	
	jQuery('select').select2();
	

   
	SBK.playlist_sort = {
			move_set_song: function (direction, button) {
		        var current_row, current_form, previous_row, row_before_previous_row, next_row, row_after_next_row, target, order_0, order_1, new_sort_order;
		       
		       current_row = button.closest('tr');
		       current_form = current_row.prev();
               previous_row = current_row.prev().prev();
               row_before_previous_row = previous_row.prev().prev();
               next_row = current_row.next().next();
               row_after_next_row = next_row.next().next();
		       if(direction === 'up') {
		          target = [row_before_previous_row, previous_row];
		       } else if(direction === 'down') {
		    	   target = [next_row, row_after_next_row];
		       } else {
		           return;
		       }

		       if(isNaN(jQuery('.playlist-song-order input', target[0]).val())) {
		    	   order_0 = 0;
		       } else {
		    	   order_0 = parseFloat(jQuery('.playlist-song-order input', target[0]).val());
		       }
		       if(isNaN(jQuery('.playlist-song-order input', target[1]).val())) {
		    	   order_1 = 1000000;
		       } else {
		    	   order_1 = parseFloat(jQuery('.playlist-song-order input', target[1]).val());
		       }
		       new_sort_order = order_0 + (order_1 - order_0)/2;
		       jQuery('.playlist-song-order input', current_row).val(new_sort_order);
		       console.log(new_sort_order)
		       //current_form.submit();
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
