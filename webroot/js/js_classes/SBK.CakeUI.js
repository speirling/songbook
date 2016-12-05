 SBK.CakeUI = {

	sets_sort: {
		set_order_value: function (current_row, previous_row, next_row) {
	        var order_0, order_1, new_sort_order;
	            
	        if(isNaN(jQuery('input[name=order]', previous_row).val())) {
	     	   order_0 = 0;
	        } else {
	     	   order_0 = parseFloat(jQuery('input[name=order]', previous_row).val());
	        }
	        if(isNaN(jQuery('input[name=order]', next_row).val())) {
	     	   order_1 = 1000000;
	        } else {
	     	   order_1 = parseFloat(jQuery('input[name=order]', next_row).val());
	        }
	        new_sort_order = order_0 + (order_1 - order_0)/2;
	        jQuery('input[name=order]', current_row).val(new_sort_order);
	        
	        console.log(current_row, previous_row, jQuery('input[name=order]', previous_row).val(), order_0, next_row, jQuery('input[name=order]', next_row).val(), order_1, new_sort_order);
	        current_form = jQuery('form', current_row)[0];
	        //console.log(current_form);
	        if(!isNaN(new_sort_order)) {
	           current_form.submit();
	        }
	    }
	},


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
	        var order_0, order_1, new_sort_order, new_set;
	      
	        new_set = current_row.parent().parent().attr('setnumber');
	        
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
	        jQuery('input[name="set_id"]', current_row).val(new_set);
	        
	        //console.log(new_set, current_row, previous_row, jQuery('.song-order input', previous_row).val(), order_0, next_row, jQuery('.song-order input', next_row).val(), order_1, new_sort_order);
	        current_form = jQuery('form', current_row)[0];
	        //console.log(current_form);
	        if(!isNaN(new_sort_order)) {
	            current_form.submit();
	        }
	    }
	},

	select: {
		
		clicked_row: function(event) {
			event.stopPropagation();
			//alert('row clicked');
			//alert(SBK.CakeUI.select.mark_row);
			//alert(event.target);
			//alert(event.target.closest('tr'));
			//.closest doesn't work on ipad 1
			// it looks like tr is clicked, so use parent?
			//row = event.target.closest('tr');
			row = jQuery(event.target).parent();
			//alert(row);
			SBK.CakeUI.select.mark_row(row);
			//alert('row marked');
		},

		mark_row: function(row) {
			//alert('marking');
			//alert(row);
	        jQuery(row).siblings().removeClass("ui-selected");
	        jQuery(row).addClass("ui-selected");		
	    },
		
		adjacent_row: function (direction, button) {
		        var current_row, new_selection;

		       current_row = button.closest('tr');
		       if (direction === 'up') {
		    	   new_selection = current_row.prev();
		       } else if(direction === 'down') {
		    	   new_selection = current_row.next();
		       } else {
		           return;
		       }
		       if(typeof(new_selection) === 'undefined') {
		    	   return;
		       }
		       SBK.CakeUI.select.mark_row(new_selection);
		}
	},
	
	toggleable: {
		sets: function (event) {
			event.preventDefault;
			jQuery('.related table.sets td.set-songs-container').toggle();
			return false;
		},
		make: function (container) {
			var toggle_button, content_div, add_text;

			jQuery(container).wrapInner('<div class="toggle-content"></div>');
			add_text = jQuery(container).attr('add_text');
			content_div = jQuery('.toggle-content', container);
			toggle_button = jQuery('<span class="button show">+ Add ' + add_text + '</span>').prependTo(container);
			content_div.hide();
			toggle_button.click(function (event) {
				console.log(event, content_div, container);
				content_div.toggle();
				if(content_div.is(":visible")) {
					toggle_button.html('Hide Add form');
				} else {
					toggle_button.html('+ Add ' + add_text);
				}
			});
			
		}
	
	}, 
	
	form: {
		submit_value: function (value, form_input_selector) {
			var form_input, form;
			
			form_input = jQuery(form_input_selector);
			form = form_input.closest('form');
			
			form_input.val(value);
			form.submit()
		},
		
		submit_value_json: function (value) {
			var obj, form_input, form;
console.log(value);
			obj = JSON.parse(value);
			for(form_input_selector in obj) {
				form_input = jQuery('#' + form_input_selector);
				form = form_input.closest('form');
				
				form_input.val(obj[form_input_selector]);
			}
			form.submit(); //assuming that all inputs are in the same form
		}
	}
};