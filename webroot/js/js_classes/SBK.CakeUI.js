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
		},

		tag_multi_edit: function () {
		    var selected_ids = [], song_id_inputs_html = '', index;

		    jQuery('.multi-select input:checked').each(function () {
		        selected_ids.push(jQuery(this).val());
		    });
		    for (index = 0; index < selected_ids.length; index = index + 1) {
		        song_id_inputs_html = song_id_inputs_html + '<input name=song_id[] type="hidden" value="' + selected_ids[index] + '" />';
		    };

		    jQuery('#tag_multieditor .song_id_input_holder').html(song_id_inputs_html);
		    if (selected_ids.length > 0) {
		        jQuery('#tag_multieditor').show();
		    } else {
                jQuery('#tag_multieditor').hide();
            }
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

			obj = JSON.parse(value);
			for(form_input_selector in obj) {
				form_input = jQuery('#' + form_input_selector);
				form = form_input.closest('form');

				form_input.val(obj[form_input_selector]);
			}
			form.submit(); //assuming that all inputs are in the same form
		},

		ajaxify: function (button, success_callback) {
			var form = jQuery(button).parents('form'), target = form.attr('action');

			form.removeClass('ajax-error').removeClass('ajax-success').addClass('ajax-in-progress');
			console.log('ajaxify', form.serialize(), target);
			jQuery.ajax({
                    url: target,
                    data: form.serialize(),
                    success: function (response) {
                        form.removeClass('ajax-in-progress').addClass('ajax-success');
                        setTimeout(function () {form.removeClass('ajax-success');}, 500);
                        if(response.success === true) {
                            if(typeof(success_callback) === 'function') {
                                success_callback(response, form);
                            }
                        } else {
                            console.log(response);
                            console.log(typeof(response));
                            console.log('Response indicates ajax call ' + target + ' failed');
                        }
                    },
                    error: function(error) {
                        form.removeClass('ajax-in-progress').addClass('ajax-error').delay(1500).removeClass('ajax-error');
                        console.log('ajax call ' + target + ' failed: ', error);
                    }
                }
            )
        },
        
        clear_filters: function (button) {
            var form = button.closest('form');
            
        jQuery('input', form).val('');
            jQuery('select', form).val(null).trigger('change');
            console.log(jQuery('select', form));
        }
    },

    ajaxcallback: {
        song_row: {
            set_tags: function (response, form) {
                var tags = response.tag_data, index = 0, list_of_tags = '', song_row = form.closest('tr.song-row');

                for (index = 0; index < tags.length; index= index + 1) {
                    list_of_tags = list_of_tags + '<span class="tag">' + tags[index] + '</span>';
                }
                jQuery('span.tags', song_row).html(list_of_tags);
            },
            add_key: function (response, form) {
                var performer = response.performer_data, new_key_html = '', song_row = form.closest('tr.song-row');

                    new_key_html = new_key_html + '<span class="performer">';
                    new_key_html = new_key_html + '<span class="nickname">' + performer[1]['nickname'] + '</span>';
                    new_key_html = new_key_html + '<span class="key">' + performer[0]['key'] + '</span>';
                    if(typeof(performer[0]['capo']) !== 'undefined') {
                        new_key_html = new_key_html + '<span class="capo">' + performer[0]['capo'] + '</span>';
                    }
                    new_key_html = new_key_html + '</span>';
                jQuery('td.performers', song_row).append(new_key_html);
            }
        },
        multitag: function (response, form) {
            var tag_ids = response['tag_ids'], tag_names = response['tag_names'], song_ids = response['song_ids'], song_index, tag_index, song_tag_holder;

            for (tag_index = 0; tag_index < tag_names.length; tag_index = tag_index + 1) {
                for (song_index = 0; song_index < song_ids.length; song_index = song_index + 1) {
                    song_tag_holder = jQuery('#song_id_' + song_ids[song_index] + ' .song-main .tags');
                    existing_tag = jQuery("span.tag:contains('" + tag_names[tag_index] + "')", song_tag_holder).length;
                    if(existing_tag == 0) {
                        song_tag_holder.append('<span class="tag">' + tag_names[tag_index] + '</span>');
                    }
                }
            }
        },
        song_view: {
            set_tags: function (response, form) {

            }
        },
        register_performance: function (response, button) {
            var song_row = button.closest('.song-row');

            song_row.addClass('played');
        },
        register_vote: function (response, button) {
            var song_row = button.closest('.song-row');

            song_row.addClass('voted');
        }
    }
};