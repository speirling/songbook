SBK.PlayListText = SBK.PlayList.extend({

	get_template: function (callback) {
		var self = this;
		
		return '#jsr-playlist-text';
	},

	display_content: function () {
		var self = this;
		
		self.container.html(self.playlist_html);
	}
});