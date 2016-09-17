 SBK.CakeUI = {

	playlist_sort: {
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
	       SBK.CakeUI.playlist_sort.set_order_value(current_row, preceding_row, following_row);
	    },

		set_order_value: function (current_row, previous_row, next_row) {
	        var order_0, order_1, new_sort_order;
	      
	       if(isNaN(jQuery('.song-order input', previous_row).val())) {
	    	   order_0 = 0;
	       } else {
	    	   order_0 = parseFloat(jQuery('.song-order input', previous_row).val());
	       }
	       if(isNaN(jQuery('.song-order input', next_row).val())) {
	    	   order_1 = 1000000;
	       } else {
	    	   order_1 = parseFloat(jQuery('.song-order input', next_row).val());
	       }
	       new_sort_order = order_0 + (order_1 - order_0)/2;
	       jQuery('.song-order input', current_row).val(new_sort_order);
	       console.log(current_row, previous_row, jQuery('.song-order input', previous_row).val(), order_0, next_row, jQuery('.song-order input', next_row).val(), order_1, new_sort_order);
	       current_form = jQuery('form', current_row);
	       console.log(current_form);
	       if(!isNaN(new_sort_order)) {
	           current_form.submit();
	       }
	    }
	},

	select: {
		
		clicked_row: function(event) {
			event.stopPropagation();
			SBK.CakeUI.select.mark_row(event.target.closest('tr'));
		},

		mark_row: function(row) {
			console.log(row);
	        jQuery(row).siblings().removeClass("ui-selected");
	        jQuery(row).addClass("ui-selected");
			console.log(row, jQuery(row));
		},
		
		adjacent_row: function (direction, button) {
		        var current_row, new_selection;

		       current_row = button.closest('tr');
		       if(direction === 'up') {
		    	   new_selection = current_row.prev();
		       } else if(direction === 'down') {
		    	   new_selection = current_row.next();
		       } else {
		           return;
		       }
		       if(typeof(new_selection) === 'undefined') {
		    	   return;
		       }
		       console.log(button, new_selection);
		       SBK.CakeUI.select.mark_row(new_selection);
		}
	}
};