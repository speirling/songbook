/*global jQuery SBK alert */

SBK.PlayList = SBK.SongList.extend({
	init: function (playlist_name, container, exclusion_list, app) {
		var self = this;

		self.call_super(container, app, '', exclusion_list);
		if (typeof(playlist_name) === 'undefined' || playlist_name === null) {
			self.playlist_name = '';
		} else {
			self.playlist_name = playlist_name;
		}
		self.api_destination = 'get_playlist';
		self.fetch_parameters = {playlist_name: self.playlist_name};
		self.set_objects = [];
		self.update_timer = {};
		self.edit_buttons_visible = false;
	},

	fetch: function (callback) {
		var self = this;

		if (self.playlist_name !== '') {
			self._fetch(callback); 
		} else {
			callback({data: {
				title: '',
				act: '',
				introduction: {
					duration: '',
					text: ''
				},
				sets:[{
					label: '',
					introduction: {
						duration: '',
						text: ''
					},
					songs: []
				}]
			}});
		}
	},

	save_playlist: function () {
		var self = this;

		self.update();
		console.log(self.data_json);
		if (self.playlist_name === '') {
			if(self.data_json.title === '') {
				alert('please enter a title for the playlist');
				return;
			} else {
				self.playlist_name = self.data_json.title.replace(/ /g, '_');
				self.fetch_parameters.playlist_name = self.playlist_name;
			}
		}
		if (self.playlist_name === '') {
			alert('please enter a Playlist Name');
			return;
		} else {
			self.app.pleasewait.show();
			self.http_request.api_call(
			    'update_playlist',
			    {
			        playlist_name: self.playlist_name,
			        playlist_data: self.get_data()
			    },
			    function () {
					self.render();
				    self.app.pleasewait.hide();
	    		}
			);
		}
	},

    to_html: function (data_json) {
        var self = this, set_index, ul, input_container_title, input_container_act, internal_navigation_bar;

        self.playlist_container = jQuery('<div class="playlist"></div>');
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
        
        ul = jQuery('<ul></ul>').appendTo(self.playlist_container);
     
        for (set_index = 0; set_index < data_json.sets.length; set_index = set_index + 1) {
            /* WORKAROUND:: simplexml converts a single song into an object, not an array. make sure data_json.sets[set_index].songs is an array!!! */
            /* problem with this if there are NO songs */
            if (typeof(data_json.sets[set_index].songs) !== 'undefined') {
                data_json.sets[set_index].songs = [].concat(data_json.sets[set_index].songs);
                
                self.set_objects[set_index] = new SBK.SongListItemSet(ul, self, set_index, data_json.sets[set_index]);
                self.set_objects[set_index].render();
            }
        }
       
        return self.playlist_container;
    },
    
    make_internal_navigation_click: function (object) {
        var self = this, object_container;
        
        object_container = object.container;

        return function () {
            jQuery(window).scrollTop(object_container.offset().top - self.navigation_panel.height());
        };
    },

	get_set_names: function () {
		var self = this;
		
		return self.set_names; //defined in SBK.Class.SongList.js  filter_songlist_json_before_display()
	},

	display_content: function () {
		var self = this, button_bar, set_index, internal_navigation_bar;
	
		self.container.html('').css('padding-top', 0);
		self.navigation_panel = jQuery('<div class="navigation-panel"></div>').appendTo(self.container);

		button_bar = jQuery('<div class="button-bar flyout closed"></div>').appendTo(self.navigation_panel);
		button_bar.click(function () {
		    jQuery(this).toggleClass('closed');
		    self.container.css('padding-top', self.navigation_panel.height());
		});

        //buttons
		self.buttons = {
            close: new SBK.Button(button_bar, 'close', '&laquo; Playists', function () {self.app.display_playlist_list();}),
            all_songs: new SBK.Button(button_bar, 'all-songs', '&laquo; All songs', function () {self.app.list_all_songs();}),
            details: new SBK.Button(button_bar, 'toggle-details', 'Details', function () {self.toggle_details();}),
            edit_buttons: new SBK.Button(button_bar, 'toggle-buttons', 'Buttons', function () {self.toggle_edit_buttons();}),
            new_set: new SBK.Button(button_bar, 'add_new_set', 'Add set', function () {self.add_set();}),
            book: new SBK.Button(button_bar, 'book', 'Book', function () {self.app.playlist_book(self.playlist_name);}),
            intro: new SBK.Button(button_bar, 'toggle-intro', 'Intros', function () {self.toggle_introductions();}),
            save: new SBK.Button(button_bar, 'save', 'Save', function() {
                    self.app.pleasewait.show();
                    self.save_playlist();
                }),
            print: new SBK.Button(button_bar, 'print', 'Print', function () {self.app.playlist_print(self.playlist_name)})
		};

        // Main playlist content
        self.container.append(self.to_html(self.data_json));

        //initially... hide intros, details and (if required) edit buttons
        self.hide_introductions();
        self.hide_details();
        if (self.edit_buttons_visible === false) {
            self.hide_edit_buttons();
        }

        internal_navigation_bar = jQuery('<div class="navigation-bar button-bar"></div>').appendTo(self.navigation_panel);
     
        if ((self.data_json.sets.length > 1) || (self.data_json.sets[0].label !== '')) {
            for (set_index = 0; set_index < self.set_objects.length; set_index = set_index + 1) {
                if(typeof(self.set_objects[set_index]) !== 'undefined') {
                    new SBK.Button(internal_navigation_bar, 'set-link', self.data_json.sets[set_index].label, self.make_internal_navigation_click(self.set_objects[set_index]));
                }
            }
        } else {
            jQuery('<span class="set-link"></span>').appendTo(internal_navigation_bar);
        }
        
        self.container.css('padding-top', self.navigation_panel.height());
        if (self.set_objects.length > 0) {
            required_padding_bottom = (self.app.container.height() - self.navigation_panel.height()) - self.set_objects[self.set_objects.length - 1].container.height();
            if (required_padding_bottom > 0) {
                self.container.css('padding-bottom', required_padding_bottom);
            }
        }
	},

    on_sortable_change: function (event, ui) {
        var self = this, song_li_item;

        self.data_json = self.get_data();
    },

    toggle_introductions: function() {
        var self = this;

        if(self.buttons.intro.hasClass('open')) {
            self.hide_introductions();
            self.buttons.intro.removeClass('open');
        } else {
            self.show_introductions();
            self.buttons.intro.addClass('open');
        }
    },

    toggle_details: function() {
        var self = this;

        if(self.buttons.details.hasClass('open')) {
            self.hide_details();
            self.buttons.details.removeClass('open');
        } else {
            self.show_details();
            self.buttons.details.addClass('open');
        }
    },

    toggle_edit_buttons: function() {
        var self = this;

        if(self.buttons.edit_buttons.hasClass('open')) {
            self.hide_edit_buttons();
            self.buttons.edit_buttons.removeClass('open');
            self.edit_buttons_visible = false;
        } else {
            self.show_edit_buttons();
            self.buttons.edit_buttons.addClass('open');
            self.edit_buttons_visible = true;
        }
    },

    hide_introductions: function() {
        var self = this, set_index;
     
        self.introduction_container.hide();

        for (set_index = 0; set_index < self.set_objects.length; set_index = set_index + 1) {
            self.set_objects[set_index].hide_introductions();
        }
    },

    show_introductions: function() {
        var self = this, set_index;
     
        self.introduction_container.show();

        for (set_index = 0; set_index < self.set_objects.length; set_index = set_index + 1) {
            self.set_objects[set_index].show_introductions();
        }
    },

    show_details: function() {
        var self = this, set_index;

        for (set_index = 0; set_index < self.set_objects.length; set_index = set_index + 1) {
            self.set_objects[set_index].show_details();
        }
    },

    hide_details: function() {
        var self = this, set_index;

        for (set_index = 0; set_index < self.set_objects.length; set_index = set_index + 1) {
            self.set_objects[set_index].hide_details();
        }
    },

    show_edit_buttons: function() {
        var self = this, set_index;

        for (set_index = 0; set_index < self.set_objects.length; set_index = set_index + 1) {
            self.set_objects[set_index].show_edit_buttons();
        }
    },

    hide_edit_buttons: function() {
        var self = this, set_index;

        for (set_index = 0; set_index < self.set_objects.length; set_index = set_index + 1) {
            self.set_objects[set_index].hide_edit_buttons();
        }
    },

	add_set: function() {
		var self = this;

		self.data_json.sets[self.data_json.sets.length] = {
			label: (''),
			introduction: {duration: '', text: ''},
			songs: []
		};
		self.redraw();
	},
    
    get_data: function () {
        var self = this, set_index, data;

        data = {
            title: self.inputs.title.val(),
            act: self.inputs.act.val(),
            introduction: {
                duration: self.inputs.introduction.duration.val(),
                text: self.inputs.introduction.text.val()
            },
            sets:[]
        };

        for (set_index = 0; set_index < self.set_objects.length; set_index = set_index + 1) {
            data.sets[set_index] = self.set_objects[set_index].get_data();
        }
        
        return data;
    },
    
    display_song: function (song_list_item) {
        var self = this, navigation_panel, previous_button, previous_song, next_button, next_song, lyrics_pane, song_lyrics;

        self.container.html('').css('padding-top', 0);

        navigation_panel = jQuery('<span class="button-bar"></span>').appendTo(self.container);

        previous_song = self.get_previous_song(song_list_item.index, song_list_item.set_index);
        if (previous_song === null) {
            previous_button = new SBK.Button(navigation_panel, 'previous', '&laquo; Previous').disable();
        } else {
            previous_button = new SBK.Button(navigation_panel, 'previous', '&laquo; Previous', function () {self.display_song(previous_song)});
        }

        next_song = self.get_next_song(song_list_item.index, song_list_item.set_index);
        if (next_song === null) {
            next_button = new SBK.Button(navigation_panel, 'next', 'next &raquo;').disable();
        } else {
            next_button = new SBK.Button(navigation_panel, 'next', 'next &raquo;', function () {self.display_song(next_song)});
        }
        
        close_button = new SBK.Button(navigation_panel, 'close', 'close', function () {self.redraw();});

        lyrics_pane = jQuery('<span class="lyrics-panel"></span>').appendTo(self.container);
        song_lyrics = new SBK.SongLyricsDisplay(
            lyrics_pane,
            song_list_item.id,
            self.app,
            song_list_item.key, 
            song_list_item.capo,
            function () {
                self.redraw();
            }
        );
        song_lyrics.render();
    },
    
    display_song_picker: function (set_index) {
        var self = this, navigation_panel, previous_button, previous_song, next_button, next_song, lyrics_pane, song_lyrics;

        //before blanking the container, save any changes to data_json
        self.update();
        self.container.html('').css('padding-top', 0);
        navigation_panel = jQuery('<span class="button-bar"></span>').appendTo(self.container); // button-bar so that it gets inline styling....
        new SBK.Button(navigation_panel, 'cancel', 'cancel', function () {self.redraw();});
        
        picker_panel = jQuery('<span class="picker-panel"></span>').appendTo(self.container);
        song_picker = new SBK.SongPicker(
            picker_panel,
            self,
            set_index,
            function (set_index, songs_selected) {
                self.add_songs_to_set(set_index, songs_selected);
                self.redraw();
            },
            function () {
                self.redraw();
            }
        );
        song_picker.render();
    },
    
    get_length: function (set_index) {
        var self = this;

        return self.data_json.sets.length;
    },
    
    get_previous_song: function (song_index, set_index) {
        var self = this, current_set, previous_set, previous_song;

        current_set = self.data_json.sets[set_index];
        if (song_index === 0) {
            previous_set = self.get_previous_set(set_index);
            if (previous_set === null) {
                previous_song = null;
            } else {
                if (previous_set.songs.length > 0) {
                    previous_song = previous_set.songs[previous_set.songs.length - 1];
                    previous_song.set_index = previous_set.set_index;
                    previous_song.index = previous_set.songs.length;
                } else {
                    previous_song = null;
                }
            }
        } else {
            previous_song = current_set.songs[song_index - 1];
                previous_song.set_index = set_index;
                previous_song.index = song_index - 1;
        }
        
        return previous_song;
    },
    
    get_next_song: function (song_index, set_index) {
        var self = this, current_set, next_set, next_song;
        
        current_set = self.data_json.sets[set_index];
        if (song_index < current_set.songs.length - 1) {
            next_song = current_set.songs[song_index + 1];
            next_song.set_index = set_index;
            next_song.index = song_index + 1;
        } else {
            next_set = self.get_next_set(set_index);
            if (next_set === null) {
                next_song = null;
            } else {
                if (next_set.songs.length > 0) {
                    next_song = next_set.songs[0];
                    next_song.set_index = next_set.set_index;
                    next_song.index = 0;
                } else {
                    next_song = null;
                }
            }
        }

        return next_song;
    },
    
    get_next_set: function (set_index) {
        var self = this, next_set;

        if (set_index === self.data_json.sets.length - 1) {
            next_set = null;
        } else {
            next_set = self.data_json.sets[set_index + 1];
            next_set.set_index = set_index + 1;
        }
        
        return next_set;
    },
    
    get_previous_set: function (set_index) {
        var self = this, previous_set;

        if (set_index === 0) {
            previous_set = null;
        } else {
            previous_set = self.data_json.sets[set_index - 1];
            previous_set.set_index = set_index - 1;
        }
        
        return previous_set;
    },
    
    remove_song: function (song_details) {

        self.data_json.sets[song_details.set_index].songs.splice(song_details.index, 1);
        self.redraw();
    },
    
    add_songs_to_set: function (set_index, songs_selected) {
        var self = this;

        jQuery.merge(self.data_json.sets[set_index].songs, songs_selected);
        
        self.redraw();
    },

    remove_set: function (set_details) {
        var self = this;

        self.data_json.sets.splice(set_details.set_index, 1);
        self.redraw();
    },

    move_song_up_one: function (song_details) {
        var self = this, starting_index, insert_details, destination_set_index, destination_index_within_set;

        starting_index = song_details.index;

        if (starting_index === 0) {
            new_set = self.get_previous_set(song_details.set_index);
            if(new_set === null) {
                destination_set_index = null;
            } else {
                destination_set_index = new_set.set_index;
                destination_index_within_set = new_set.songs.length;
            }
        } else {
            destination_set_index = song_details.set_index;
            destination_index_within_set = starting_index - 1;
        }

        if (destination_set_index !== null) {
            self.move_song_to(song_details, destination_set_index, destination_index_within_set);
        }
    },

    move_song_down_one: function (song_details) {
        var self = this, starting_index, insert_details, destination_set_index, destination_index_within_set;

        starting_index = song_details.index;

        if (starting_index === (self.data_json.sets[song_details.set_index].songs.length - 1)) {
            new_set = self.get_next_set(song_details.set_index);
            if(new_set === null) {
                destination_set_index = null;
            } else {
                destination_set_index = new_set.set_index;
                destination_index_within_set = 0;
            }
        } else {
            destination_set_index = song_details.set_index;
            destination_index_within_set = starting_index + 1;
        }

        if (destination_set_index !== null) {
            self.move_song_to(song_details, destination_set_index, destination_index_within_set);
        }
    },

    display_move_song_destination_chooser: function (song_details) {
        var self = this, starting_index, insert_details, set_index, set_row;

        self.container.html('').css('padding-top', 0);
        navigation_panel = jQuery('<span class="button-bar"></span>').appendTo(self.container);

        //close button
        new SBK.Button(navigation_panel, 'close', 'close', function () {self.redraw();});
        
        chooser_panel = jQuery('<span class="chooser-panel"></span>').appendTo(self.container);

        for (set_index = 0; set_index < self.data_json.sets.length; set_index = set_index + 1) {
            console.log(self.data_json.sets[set_index]);
            set_row = jQuery('<div class="set-row"><div>').appendTo(chooser_panel);
            jQuery('<label>' + self.data_json.sets[set_index].label + '</label>').appendTo(set_row);

            new SBK.Button(set_row, 'move-to-start', 'move to start', self.make_destination_click_function(song_details, set_index, 0));
            new SBK.Button(set_row, 'move-to-end', 'move to end', self.make_destination_click_function(song_details, set_index, self.data_json.sets[set_index].songs.length));
        }
    },
    
    make_destination_click_function: function (song_details, set_index, song_index) {
        var self = this;
        
        return function () {
            self.move_song_to(song_details, set_index, song_index);
            self.redraw();
        }
    },

    move_song_to: function (song_details, destination_set_index, destination_index_within_set) {
        var self = this, starting_index, insert_details;

        console.log('move_song_to', song_details, destination_set_index, destination_index_within_set);
        starting_index = song_details.index;
        insert_details = self.data_json.sets[song_details.set_index].songs[starting_index];
        self.data_json.sets[song_details.set_index].songs.splice(starting_index, 1);
        self.data_json.sets[destination_set_index].songs.splice(destination_index_within_set, 0, insert_details);
        self.redraw();
    }
});