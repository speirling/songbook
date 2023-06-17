/*global jQuery SBK alert */

SBK.PlayList.Edit = SBK.PlayList.extend({
    to_html: function (data_json) {
        var self = this, set_index, ul, input_container_title, input_container_act, internal_navigation_bar, filter_to;

        self.playlist_container = jQuery('<div class="playlist edit"></div>');
        input_container_title = jQuery('<span class="playlist-title"><label>Playlist: </label></span>').appendTo(self.playlist_container);
        input_container_act = jQuery('<span class="playlist-act"><label>Act: </label></span>').appendTo(self.playlist_container);
        self.inputs = {
            title: jQuery('<input type="text" class="playlist-title" size="20" placeholder="playlist title" value="' + self.value_or_blank(data_json.title) + '" />').appendTo(input_container_title),
            act: jQuery('<input type="text" class="act" size="20" placeholder="act" value="' + self.value_or_blank(data_json.act) + '" />').appendTo(input_container_act)
        };
        self.introduction_container = jQuery('<span class="introduction songlist" style="display: none"></span>').appendTo(self.playlist_container);
        self.inputs.introduction = {
            text: jQuery('<textarea class="introduction_text" placeholder="Introduction text">' + self.value_or_blank(data_json.introduction.text) + '</textarea>').appendTo(self.introduction_container),
            duration: jQuery('<input type="text" class="introduction_duration" placeholder="Introduction duration" value="' + self.value_or_blank(data_json.introduction.duration) + '" />').appendTo(self.introduction_container)
        };
        
        self.playlist_ul = jQuery('<ul></ul>').appendTo(self.playlist_container);

        if (typeof(data_json.sets) !== 'undefined') { //could happen when a playlist is first defined
            data_json.sets = [].concat(data_json.sets); //same issue as WORKAROUND below
            for (set_index = 0; set_index < data_json.sets.length; set_index = set_index + 1) {
                /* WORKAROUND:: simplexml converts a single song into an object, not an array. make sure data_json.sets[set_index].songs is an array!!! */
                /* problem with this if there are NO songs */
                if (typeof(data_json.sets[set_index].songs) !== 'undefined') {
                    data_json.sets[set_index].songs = [].concat(data_json.sets[set_index].songs);
                }
                self.set_objects[set_index] = new SBK.SongListItemSet.Edit(self.playlist_ul, self, set_index, data_json.sets[set_index]);
                self.set_objects[set_index].render();
                self.make_draggable(self.playlist_ul, 'span.set-title label', '.playlist > ul');
            }
        } else {
            console.log('no sets');
        }

        return self.playlist_container;
    },

	display_content: function () {
		var self = this, button_bar, set_index, internal_navigation_bar, label, filter_to;

		self.container.html('').css('padding-top', 0); //so that the navigation bar is always at the top of the screen
		self.navigation_panel = jQuery('<div class="navigation-panel"></div>').appendTo(self.container);

		button_bar = jQuery('<div class="sb-button-bar flyout closed"></div>').appendTo(self.navigation_panel);
		button_bar.click(function () {self.button_bar_toggle(this);});

        //buttons
		self.buttons = self.insert_buttons(button_bar);

        // Main playlist content
        self.container.append(self.to_html(self.data_json));

        //initially... hide intros, details and (if required) edit buttons
        if (self.app.application_state.introductions_visible_in_list) {
            self.show_introductions();
        } else {
            self.hide_introductions();
        }
        if (self.app.application_state.buttons_visible_in_list) {
            self.show_edit_buttons();
        } else {
            self.hide_edit_buttons();
        }
        if (self.app.application_state.details_visible_in_list) {
            self.show_details();
        } else {
            self.hide_details();
        }

        internal_navigation_bar = jQuery('<div class="internal-navigation navigation-bar sb-button-bar flyout closed"></div>').appendTo(self.navigation_panel);
     
        if (typeof(self.data_json.sets) !== 'undefined' && self.data_json.sets.length > 0) {
            for (set_index = 0; set_index < self.set_objects.length; set_index = set_index + 1) {
                if(typeof(self.set_objects[set_index]) !== 'undefined') {
                    if (self.data_json.sets[set_index].label === '') {
                        label = '(untitled)';
                    } else {
                        label = self.data_json.sets[set_index].label;
                    }
                    new SBK.Button(internal_navigation_bar, 'set-link', label, self.make_internal_navigation_click(self.set_objects[set_index]));
                }
            }
        } else {
            jQuery('<span class="set-link"></span>').appendTo(internal_navigation_bar);
        }
        internal_navigation_bar.click(function () {self.button_bar_toggle(this);});

        self.filter = jQuery('<div class="picker-filter"></div>').appendTo(self.navigation_panel);
        filter_border = jQuery('<div class="picker-filter-border"></div>').appendTo(self.filter);
        filter_wrapper = jQuery('<div class="picker-filter-wrapper"></div>').appendTo(filter_border);
        self.filter_input = jQuery('<input type="text" placeholder="type to filter"/>').appendTo(filter_wrapper);
        self.filter_clear = jQuery('<span class="icon-close"></span>').appendTo(filter_border);
        count_container = jQuery('<span class="number"><label> songs displayed</label></span>').appendTo(self.filter);
        self.number_of_songs = jQuery('<span class="number-of-records"></span>').prependTo(count_container);

        self.filter_input.unbind('keyup').keyup(function () {
            if (filter_to) {
                clearTimeout(filter_to);
            }
            filter_to = setTimeout(function () {
                filter_value = self.filter_input.val();
                self.filter_playlist_songs(filter_value);
            }, 500);
        });

        self.filter_clear.bind('click', function () {
            self.filter_input.val('');
            self.filter_playlist_songs('');
        });

        self.number_of_songs.html(jQuery('li.song:visible', self.container).length);
        
        self.container.css('padding-top', self.navigation_panel.height());  //so that the navigation bar is always at the top of the screen
        if (self.set_objects.length > 0) {
            required_padding_bottom = (self.app.container.height() - self.navigation_panel.height()) - self.set_objects[self.set_objects.length - 1].container.height();
            if (required_padding_bottom > 0) {
                self.container.css('padding-bottom', required_padding_bottom);
            }
        }
        self.set_playlist_container_top(); 
	},
    
    make_draggable: function (container, click_selector, connect_selector, sortable_change_callback) {
        var self = this;

        // This procedure was taken from Aaron Blenkush on http://stackoverflow.com/questions/3774755/jquery-sortable-select-and-drag-multiple-list-items
        container.on('click', click_selector, function (e) {
            var clicked_handle = jQuery(this), selected_li, local_list; //local_list may or may not be the same as container 

            selected_li = clicked_handle.closest('li');
            local_list = clicked_handle.closest('ul, ol');

            /*if (e.ctrlKey || e.metaKey) {
                selected_li.toggleClass("selected");
                jQuery('ol', local_list).each(function() {
                    if (!jQuery.contains(this, selected_li[0])) {
                        jQuery('li', this).removeClass('selected');
                    }
                });
            } else {*/
                jQuery('li', local_list).removeClass('selected');
                selected_li.addClass("selected");
            /*}*/
        });

        container.sortable({
            connectWith: connect_selector,
            delay: 150, //Needed to prevent accidental drag when trying to select
            revert: 0,
            cursor: 'move',
 /*  multiselect stops the sets from sorting separately from songs. With these commented out, only a single song can be selected, but it works the way you'd expect. 
            helper: function (e, item) {
                var elements, helper;
console.log(e, item);
                //item.closest('ul, ol').addClass('being_dragged');
                //Basically, if you grab an unhighlighted item to drag, it will deselect (unhighlight) everything else
                if (!item.hasClass('selected')) {
                    item.addClass('selected').siblings().removeClass('selected');
                }

                //////////////////////////////////////////////////////////////////////
                //HERE'S HOW TO PASS THE SELECTED ITEMS TO THE `stop()` FUNCTION:

                //Clone the selected items into an array
                elements = item.parent().children('.selected').clone();

                //Add a property to `item` called 'multidrag` that contains the
                //  selected items, then remove the selected items from the source list
                item.data('multidrag', elements).siblings('.selected').remove();

                //Now the selected items exist in memory, attached to the `item`,
                //  so we can access them later when we get to the `stop()` callback

                //Create the helper
                helper =  jQuery('<li/>');
                return helper.append(elements);
            },*/
            start: function (e, ui) {
                 ui.item.closest('ul, ol').addClass('being_dragged');
             },
            stop: function (e, ui) {
                /* var elements;
    
                 //Now we access those items that we stored in `item`s data!
                 elements = ui.item.data('multidrag');
    
                 //`elements` now contains the originally selected items from the source list (the dragged items)!!
    
                 //Finally I insert the selected items after the `item`, then remove the `item`, since
                 //  item is a duplicate of one of the selected items.
                 ui.item.after(elements).remove();
                 //container.sortable('destroy');*/
                 ui.item.closest('ul, ol').removeClass('being_dragged');
                 self.on_sortable_change();
             }
        });
    }
});