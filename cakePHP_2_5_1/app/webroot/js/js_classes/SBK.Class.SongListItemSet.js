/*global jQuery SBK alert */

SBK.SongListItemSet = SBK.Class.extend({
	init: function (parent_container, playlist, index_in_playlist, data) {
		var self = this;

		self.parent_container = parent_container;
		self.data = data;
		self.song_objects = [];
		self.index = index_in_playlist;
		self.playlist = playlist;
	},

    render: function () {
        var self = this, song_index, set_ol, title_input_holder;

        self.container = jQuery('<li class="set" id="set_' + self.index + '"></li>').appendTo(self.parent_container);
        self.button_bar = jQuery('<div class="button-bar"></div>').appendTo(self.container);
        title_input_holder = jQuery('<span class="set-title"><label>Set: </label></span>').appendTo(self.container);
        self.inputs = {
           title: jQuery('<input type="text" class="set-title" placeholder="set title" value="' + self.playlist.value_or_blank(self.data.label) + '" />').appendTo(title_input_holder)
        };
        jQuery('<span class="duration"></span>').appendTo(self.container);
        
        self.buttons = {
            remove: new SBK.Button(self.button_bar, 'remove', 'remove this set', function () {self.playlist.remove_set({set_index: self.index});}),
            add_song: new SBK.Button(self.button_bar, 'add', 'add songs to this set', function () {self.playlist.display_song_picker(self.index);})
        };

        self.introduction_container = jQuery('<span class="introduction set" style="display: none"></span>').appendTo(self.container);
        if (typeof(self.data.introduction) !== 'undefined') {
            self.inputs.introduction = {
                text: jQuery('<textarea class="introduction_text" placeholder="Introduction text">' + self.playlist.value_or_blank(self.data.introduction.text) + '</textarea>').appendTo(self.introduction_container),
                duration: jQuery('<input type="text" class="introduction_duration" placeholder="Introduction duration" value="' + self.playlist.value_or_blank(self.data.introduction.duration) + '" />').appendTo(self.introduction_container)
            };
        }
        set_ol = jQuery('<ol class="songlist"></ol>').appendTo(self.container);

        for (song_index = 0; song_index < self.data.songs.length; song_index = song_index + 1) {
            self.song_objects[song_index] = new SBK.SongListItemSong(
                set_ol, 
                self,
                song_index,
                self.data.songs[song_index]
            );
            self.song_objects[song_index].render();
        }
    },
    
    get_data: function () {
        var self = this, song_index, data;

        data = {
            label: self.inputs.title.val(),
            introduction: {
                duration: self.inputs.introduction.duration.val(),
                text: self.inputs.introduction.text.val()
            },
            songs: []
        };
        for (song_index = 0; song_index < self.song_objects.length; song_index = song_index + 1) {
            data.songs[song_index] = self.song_objects[song_index].get_data();
        }
        
        return data;
    },
    
    get_next_song: function (index) {
        var self = this, next_set, next_song_list_item = null;
        
        if (index === self.data.songs.length - 1) {
            next_set = playlist.get_next_set(self.index);
            if (next_set != null) {
            next_song = new SBK.SongListItemSong(
                    null, 
                    self,
                    0,
                    next_set.get_first_song()
                );
            }
        } else {
            next_song = new SBK.SongListItemSong(
                null, 
                self,
                index + 1,
                self.data.songs[index + 1]
            );
        }
        
        return next_song;
    },
    
    get_previous_song: function (index) {
        var self = this, previous_set, previous_song = null;
        
        if (index === 0) {
            previous_set = playlist.get_previous_set(self.index);
            if (previous_set != null) {
                previous_song = new SBK.SongListItemSong(
                    null, 
                    self,
                    previous_set.get_length(),
                    previous_set.get_last_song()
                );
            }
        } else {
            previous_song = new SBK.SongListItemSong(
                null, 
                self,
                index - 1,
                self.data.songs[index - 1]
            );
        }
        
        return previous_song;
    },
    
    get_first_song: function (index) {
        var self = this;
        
        return self.data.songs[0];
    },
    
    get_last_song: function (index) {
        var self = this;

        return self.data.songs[self.data.songs.length - 1];
    },

    hide_introductions: function() {
        var self = this, song_index;

        if(typeof(self.introduction_container) !== 'undefined') {
            self.introduction_container.hide();
        }

        for (song_index = 0; song_index < self.song_objects.length; song_index = song_index + 1) {
            self.song_objects[song_index].hide_introductions();
        }
    },

    show_introductions: function() {
        var self = this, song_index;
     
        self.introduction_container.show();

        for (song_index = 0; song_index < self.song_objects.length; song_index = song_index + 1) {
            self.song_objects[song_index].show_introductions();
        }
    },

    show_details: function() {
        var self = this, song_index;

        for (song_index = 0; song_index < self.song_objects.length; song_index = song_index + 1) {
            self.song_objects[song_index].show_details();
        }
    },

    hide_details: function() {
        var self = this, song_index;

        for (song_index = 0; song_index < self.song_objects.length; song_index = song_index + 1) {
            self.song_objects[song_index].hide_details();
        }
    },

    show_edit_buttons: function() {
        var self = this, song_index;
        
        self.button_bar.show();

        for (song_index = 0; song_index < self.song_objects.length; song_index = song_index + 1) {
            self.song_objects[song_index].show_buttons();
        }
    },

    hide_edit_buttons: function() {
        var self = this, song_index;

        self.button_bar.hide();

        for (song_index = 0; song_index < self.song_objects.length; song_index = song_index + 1) {
            self.song_objects[song_index].hide_buttons();
        }
    }
});