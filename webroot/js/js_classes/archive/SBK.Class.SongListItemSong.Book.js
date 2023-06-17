/*global jQuery SBK alert */

SBK.SongListItemSong.Book = SBK.SongListItemSong.extend({

	init: function (parent_container, set, index_in_set, data) {
		var self = this;

		self.container = jQuery('<li class="song" id="' + data.id + '"></li>').appendTo(parent_container);
		if (data.filter_display === true) {
		    self.container.addClass('filter-display');
		}
		self.data = data;
        self.set = set;
        self.index = index_in_set;
		self.playlist = self.set.playlist;
	},

    render: function () {
        var self = this, song_introduction_container;

        jQuery('<span class="title">' + self.playlist.value_or_blank(self.data.title) + '</span>').appendTo(self.container);
        jQuery('<span class="id">(' + self.playlist.value_or_blank(self.data.id) + ')</span>').appendTo(self.container);
        if(typeof(self.data.singer) !== 'undefined') {
            jQuery('<span class="singer">(' + self.playlist.value_or_blank(self.data.singer) + ')</span>').appendTo(self.container);
        }
        if(typeof(self.data.key) !== 'undefined') {
            jQuery('<span class="key">(' + self.playlist.value_or_blank(self.data.key) + ')</span>').appendTo(self.container);
        }
    }
});