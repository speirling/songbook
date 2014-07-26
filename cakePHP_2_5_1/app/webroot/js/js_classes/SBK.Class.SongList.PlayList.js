/*global jQuery SBK alert */

SBK.PlayList = SBK.SongList.extend({
	init: function (playlist_name, container, exclusion_list, app) {
		var self = this;

		self.call_super(container, '', exclusion_list);
		if (typeof(playlist_name) === 'undefined' || playlist_name === null) {
			self.playlist_name = '';
		} else {
			self.playlist_name = playlist_name;
		}
		self.api_destination = 'get_playlist';
		self.fetch_parameters = {playlist_name: self.playlist_name};
		self.set_objects = [];
		if (typeof(app) === 'undefined') {
            self.app = null;
		} else {
		    self.app = app;
		}
		self.update_timer = {};
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
			self.pleasewait.show();
			self.http_request.api_call(
			    'update_playlist',
			    {
			        playlist_name: self.playlist_name,
			        playlist_data: self.get_data()
			    },
			    function () {
					self.render();
				    self.pleasewait.hide();
	    		}
			);
		}
	},

    to_html: function (data_json) {
        var self = this, set_index, playlist_container, ul;

        playlist_container = jQuery('<div class="playlist"></div>');
        self.inputs = {
            title: jQuery('<input type="text" class="playlist-title" placeholder="playlist title" value="' + self.value_or_blank(data_json.title) + '" />').appendTo(playlist_container),
            act: jQuery('<input type="text" class="act" placeholder="act" value="' + self.value_or_blank(data_json.act) + '" />').appendTo(playlist_container)
        };
        self.introduction_container = jQuery('<span class="introduction songlist" style="display: none"></span>').appendTo(playlist_container);
        self.inputs.introduction = {
            text: jQuery('<textarea class="introduction_text" placeholder="Introduction text">' + self.value_or_blank(data_json.introduction.text) + '</textarea>').appendTo(self.introduction_container),
            duration: jQuery('<input type="text" class="introduction_duration" placeholder="Introduction duration" value="' + self.value_or_blank(data_json.introduction.duration) + '" />').appendTo(self.introduction_container)
        };
        
        ul = jQuery('<ul></ul>').appendTo(playlist_container);
     
        for (set_index = 0; set_index < data_json.sets.length; set_index = set_index + 1) {
            /* WORKAROUND:: simplexml converts a single song into an object, not an array. make sure data_json.sets[set_index].songs is an array!!! */
            data_json.sets[set_index].songs = [].concat(data_json.sets[set_index].songs);
            
            self.set_objects[set_index] = new SBK.SongListItemSet(ul, self, set_index, data_json.sets[set_index]);
            self.set_objects[set_index].render();
        }
       
        return playlist_container;
    },

	get_set_names: function () {
		var self = this;
		
		return self.set_names; //defined in SBK.Class.SongList.js  filter_songlist_json_before_display()
	},

	display_content: function () {
		var self = this, button_bar;
	
		self.container.html('');
		button_bar = jQuery('<span class="button-bar"></span>').appendTo(self.container);
		console.log(self.data_json);
		self.container.append(self.to_html(self.data_json));

		// set up buttons
        self.close_playlist = jQuery('<a class="button close">&laquo; List all Playists</a>').appendTo(button_bar).click(function() {
            self.app.display_playlist_list();
        });
        self.save_button = jQuery('<a class="button save">Save</a>').appendTo(button_bar).click(function() {
            self.pleasewait.show();
            self.save_playlist();
        });
		self.toggle_intro_button = jQuery('<a class="button toggl-intro">Toggle all Introductions</a>').appendTo(button_bar).click(function() {
			self.toggle_introductions();
		});
		self.add_set_button = jQuery('<a class="button add_new_set">Add a new set</a>').appendTo(button_bar).click(function() {
			self.add_set();
		});
		self.hide_introductions();
		
		jQuery('.playlist ul', self.container).sortable({ 
            update: function (event, ui) {
                self.on_sortable_change(event, ui);
            }
        });
		jQuery('.songlist', self.container).sortable({
		    connectWith: '.songlist', 
		    update: function (event, ui) {
		        self.on_sortable_change(event, ui);
		    }
		});
	},

    on_sortable_change: function (event, ui) {
        var self = this, song_li_item;

        self.data_json = self.get_data();
    },

	toggle_introductions: function() {
		var self = this;

		if(self.toggle_intro_button.hasClass('open')) {
		    self.hide_introductions();
		    self.toggle_intro_button.removeClass('open');
		} else {
			self.show_introductions();
			self.toggle_intro_button.addClass('open');
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
    
    value_or_blank: function (value) {
        var self = this, result;
        
        if (typeof(value) === 'string') {
            result = value;
        } else {
            result = '';
        }
        
        return result;
    },
    
    display_song: function (song_list_item) {
        var self = this, navigation_panel, previous_button, previous_song, next_button, next_song, lyrics_pane, song_lyrics;

        if (typeof(self.dialog_frame) === 'undefined') {
            self.dialog_frame = jQuery('<div class="song-frame"></div>').appendTo(self.container);
        }
        self.dialog_frame.html('').show();
        navigation_panel = jQuery('<span class="navigation-panel"></span>').appendTo(self.dialog_frame);
        previous_button = jQuery('<span class="button previous">&laquo; Previous</span>').appendTo(navigation_panel);
        next_button = jQuery('<span class="button next">next &raquo;</span>').appendTo(navigation_panel);
        close_button = jQuery('<span class="button close">close</span>').appendTo(navigation_panel).click(function () {
            self.dialog_frame.hide();
        });
        
        next_song = self.get_next_song(song_list_item.index, song_list_item.set_index);
        if (next_song === null) {
            next_button.addClass('disabled');
        } else {
            next_button.click(function () {
                self.display_song(next_song);
            });
        }
        
        previous_song = self.get_previous_song(song_list_item.index, song_list_item.set_index);
        if (previous_song === null) {
            previous_button.addClass('disabled');
        } else {
            previous_button.click(function () {
                self.display_song(previous_song);
            });
        }
        
        lyrics_pane = jQuery('<span class="lyrics-panel"></span>').appendTo(self.dialog_frame);
        song_lyrics = new SBK.SongLyricsDisplay(
            lyrics_pane,
            song_list_item.id,
            self.app,
            song_list_item.key, 
            song_list_item.capo,
            function () {
                self.dialog_frame.hide();
            }
        );
        song_lyrics.render();
    },
    
    display_song_picker: function (set_index) {
        var self = this, navigation_panel, previous_button, previous_song, next_button, next_song, lyrics_pane, song_lyrics;

        self.dialog_frame = jQuery('<div class="song-picker-frame"></div>').appendTo(self.container);
        navigation_panel = jQuery('<span class="navigation-panel"></span>').appendTo(self.dialog_frame);
        cancel_button = jQuery('<span class="button cancel">cancel</span>').appendTo(navigation_panel).click(function () {
            self.dialog_frame.remove();
        });
        /*save_button = jQuery('<span class="button save">save</span>').appendTo(navigation_panel).click(function () {
            
        });*/
        
        picker_panel = jQuery('<span class="picker-panel"></span>').appendTo(self.dialog_frame);
        song_picker = new SBK.SongPicker(
            picker_panel,
            self,
            set_index,
            function (set_index, songs_selected) {
                self.add_songs_to_set(set_index, songs_selected);
                self.dialog_frame.remove();
            },
            function () {
                self.dialog_frame.remove();
            }
        );
        song_picker.render();
    },
    
    add_songs_to_set: function (set_index, songs_selected) {
        var self = this;

        console.log(set_index, songs_selected, self.data_json.sets[set_index]);
        jQuery.merge(self.data_json.sets[set_index].songs, songs_selected);
        console.log(self.data_json.sets[set_index].songs);
        
        self.redraw();
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
        var self = this;

        self.data_json.sets[song_details.set_index].songs.splice(song_details.index, 1);
        self.redraw();
    },
    
    remove_set: function (set_details) {
        var self = this;

        self.data_json.sets.splice(set_details.set_index, 1);
        self.redraw();
    }
});