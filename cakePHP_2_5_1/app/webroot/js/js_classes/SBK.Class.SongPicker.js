SBK.SongPicker = SBK.Class.extend({
    init: function (container, playlist, set_index, on_save, on_cancel) {
        var self = this;

        self.container = container;
        self.pleasewait = new SBK.PleaseWait(self.container);
        self.app = playlist.app;
        self.api = self.app.api;

        self.linked_playlist = null;
        self.playlist = playlist,
        self.set_index = set_index;
        self.on_save = on_save;
        self.on_cancel = on_cancel;
    },

	link_to_playlist: function (playlist) {
		var self = this;

		self.linked_playlist = playlist;
	},

	get_exclusion_list: function () {
		var self = this, exclusion_list;

		if(self.linked_playlist === null) {
			exclusion_list = null;
		} else {
			exclusion_list = self.linked_playlist;
		}

		return exclusion_list;
	},
	
	render: function () {
		var self = this, exclusion_list;

		self.container.html('');
        self.submit_button = new SBK.Button(self.container, 'submit', 'Add the selected songs to the playlist', function () {self.on_save(self.set_index, self.song_list.get_selected());});
        self.playlist_picker_holder = jQuery('<div class="playlist-picker"></div>').appendTo(self.container);
        self.song_list_holder = jQuery('<div class="available-songs"></div>').appendTo(self.container);
		self.pleasewait.show();
	    self.display_playlist_picker();
	    self.show_all_songs();
	},
	
	display_playlist_picker: function () {
		var self = this;

		new SBK.PlaylistPicker(self.playlist_picker_holder, self.app).render(
			function (list) {
				list.change(function () {
					var value = jQuery(this).val();
					if (value === 'all') {
						self.show_all_songs();
					} else {
						self.show_playlist(value);
					}
        		});
				self.pleasewait.hide();
			}
		);
	},
	
	show_all_songs: function () {
		var self = this;

		self.song_list = new SBK.SongFilterList(self.song_list_holder, self.playlist.app, self.get_exclusion_list(), self.playlist);
		self.song_list.render();
	},
    
    show_playlist: function (playlist) {
        var self = this;

        self.song_list = new SBK.PlayList.Selector(playlist, self.song_list_holder, self.get_exclusion_list(), self.playlist.app);
        self.song_list.render();
    }
});
