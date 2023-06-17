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
        title_input_holder = jQuery('<span class="set-title"><label><span>Set: </span></label></span>').appendTo(self.container);
        self.title=  jQuery('<span type="text" class="set-title">' + self.playlist.value_or_blank(self.data.label) + '</span>').appendTo(title_input_holder)

        set_ol = jQuery('<ol class="songlist"></ol>').appendTo(self.container);

        if (typeof(self.data.songs) !== 'undefined') {
            for (song_index = 0; song_index < self.data.songs.length; song_index = song_index + 1) {
                self.song_objects[song_index] = new SBK.SongListItemSong(
                    set_ol, 
                    self,
                    song_index,
                    self.data.songs[song_index]
                );
                self.song_objects[song_index].render();
            }
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
    }
});