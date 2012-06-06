SBK.SongPicker = SBK.Class.extend({
	init: function (container) {
		var self = this;

		self.container = container;
		self.template = jQuery('#jsr-playlist-picker');
		self.pleasewait = new SBK.PleaseWait(self.container);
		self.http_request = new SBK.HTTPRequest();
		
		self.playlist_picker_holder = jQuery('<div id="playlist-picker"></div>').appendTo(self.container);
		self.song_list_holder = jQuery('<div id="available-songs"></div>').appendTo(self.container);
	},
	
	render: function () {
		var self = this;
		
		self.pleasewait.show();
		self.http_request.api_call(
		    {action: 'get_available_playlists'},
		    function (response) {
		    	self.display_playlist_picker(response.data);
		    	self.show_all_songs();
		    	self.pleasewait.hide();
    		}
		);
	},
	
	display_playlist_picker: function (data) {
		var self = this;

		self.picker = jQuery(self.template.render(data)).appendTo(self.playlist_picker_holder);
		self.picker.change(function () {
			var value = jQuery(this).val(), available_song_list;
			if (value === 'all') {
				self.show_all_songs();
			} else {
				self.show_playlist(value);
			}
		});
	},
	
	show_all_songs: function () {
		var self = this;
		
		new SBK.SongFilterList(self.song_list_holder).render();
	},
	
	show_playlist: function (playlist) {
		var self = this;
		
		new SBK.PlayList(playlist, self.song_list_holder).render();
	}
});