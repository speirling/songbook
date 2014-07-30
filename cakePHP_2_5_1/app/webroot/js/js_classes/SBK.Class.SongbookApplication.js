SBK.SongbookApplication = SBK.Class.extend({
	init: function (container) {
		var self = this;

		self.container = container;       
        self.song = null;
        self.set = null;
        self.playlist = null;

	},
	
	render: function (callback) {
		var self = this, all_playlists;
		
		self.container.html('');
        // Set the initial application state
        self.application_state = new SBK.ApplicationState();
        self.application_state.register_callback(function () {
            self.on_application_state_change();
        });
        self.application_state.startup(self);
	},
    
    on_application_state_change: function () {
        var self = this;
        
        self.container.html('');
        delete self.content_container;
        self.tab = self.application_state.tab;
        if (self.tab === 'playlist_list') {
            self._display_playlist_list();
        } else if (self.tab === 'edit_playlist') {
            self._display_playlist();
        } else if (self.tab === 'playlist_print') {
            self._playlist_print();
        } else if (self.tab === 'playlist_book') {
            self._playlist_book();
        } else if (self.tab === 'song_lyrics') {
            self._display_lyrics();
        } else if (self.tab === 'edit_song') {
            self._edit_song();
        } else if (self.tab === 'list_all_songs') {
            self._list_all_songs();
        }
    },

    list_all_songs: function () {
        var self = this;

        self.application_state.set({
            tab: 'list_all_songs',
            id: null,
            key: null,
            capo: null
        }, false);
    },

    display_playlist_list: function () {
        var self = this;

        self.application_state.set({
            tab: 'playlist_list',
            id: null,
            key: null,
            capo: null
        }, false);
    },

    display_playlist: function (filename) {
        var self = this;

        self.application_state.set({
            tab: 'edit_playlist',
            filename: filename,
            id: null,
            key: null,
            capo: null
        }, false);
        
    },

    playlist_print: function (filename) {
        var self = this;

        self.application_state.set({
            tab: 'playlist_print',
            filename: filename,
            id: null,
            key: null,
            capo: null
        }, false);
        
    },

    playlist_book: function (filename) {
        var self = this;

        self.application_state.set({
            tab: 'playlist_book',
            filename: filename,
            id: null,
            key: null,
            capo: null
        }, false);
        
    },
    
    display_song: function (song) {
        var self = this, next_song, previous_song;

        next_song = song.set.get_next_song(song.index);
        previous_song = song.set.get_previous_song(song.index);
        self.display_lyrics(song.data.id, song.data.key, song.data.capo, song.set, song.index, song.set.index, song.index);
    },

    display_lyrics: function (song_id, key, capo, new_window) {
        var self = this;

        if (typeof(new_window) !== 'undefined' && new_window === true) {
            new_window = true;
        } else {
            new_window = false;
        }

        self.application_state.set({
            tab: 'song_lyrics',
            playlist_filename: null,
            id: song_id,
            key: key,
            capo: capo
        }, new_window);
    },

    edit_song: function (song_id) {
        var self = this;

        self.application_state.set({
            tab: 'edit_song',
            filename: null,
            id: song_id,
            key: null,
            capo: null
        }, false);
    },

    back: function () {
        var self = this;

        self.application_state.back();
    },

    _list_all_songs: function () {
        var self = this;

        button_bar = jQuery('<span class="button-bar"></span>').appendTo(self.container);
        self.buttons = {
            all_playlists: jQuery('<a class="button all-songs">List all Playists</a>').appendTo(button_bar).click(function() {
                self.display_playlist_list();
            })
        };
        self.content_container = jQuery('<div id="all-songs-list"></div>').appendTo(self.container);
        self.all_songs = new SBK.SongFilterList(self.content_container, self).render();
    },

    _display_playlist_list: function () {
        var self = this;

        button_bar = jQuery('<span class="button-bar"></span>').appendTo(self.container);
        self.buttons = {
            all_songs: jQuery('<a class="button all-songs">List all songs</a>').appendTo(button_bar).click(function() {
                self.list_all_songs();
            })
        };
        self.content_container = jQuery('<div id="playlists-list"></div>').appendTo(self.container);
        self.all_playlists = new SBK.AllPlaylists(self.content_container, self).render();
    },

    _display_playlist: function (filename) {
        var self = this;

        self.content_container = jQuery('<div id="playlist-display"></div>').appendTo(self.container);
        self.playlist = new SBK.PlayList(self.application_state.playlist_filename, self.content_container, null, self);
        self.playlist.render();
    },

    _playlist_print: function (filename) {
        var self = this;

        self.content_container = jQuery('<div id="playlist-print"></div>').appendTo(self.container);
        self.playlist = new SBK.PlayListPrint(self.application_state.playlist_filename, self.content_container, null, self);
        self.playlist.render();
    },

    _playlist_book: function (filename) {
        var self = this;

        self.content_container = jQuery('<div id="playlist-book"></div>').appendTo(self.container);
        self.playlist = new SBK.PlayListBook(self.application_state.playlist_filename, self.content_container, null, self);
        self.playlist.render();
    },

    _display_lyrics: function () {
        var self = this;

        self.content_container = jQuery('<div id="lyrics-display"></div>').appendTo(self.container);
        self.song = new SBK.SongLyricsDisplay(
            self.content_container, 
            self.application_state.id,
            self,
            self.application_state.key, 
            self.application_state.capo
        );
        self.song.render();
    },

    _edit_song: function () {
        var self = this;

        self.content_container = jQuery('<div id="lyrics-edit"></div>').appendTo(self.container);
        self.song = new SBK.SongLyricsEdit(
            self.content_container, 
            self.application_state.id,
            self
        );
        self.song.render();
    }
});