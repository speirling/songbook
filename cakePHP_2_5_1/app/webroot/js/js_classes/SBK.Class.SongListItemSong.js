/*global jQuery SBK alert */

SBK.SongListItemSong = SBK.Class.extend({
	init: function (parent_container, set, index_in_set, data) {
		var self = this;

		self.container = jQuery('<li class="song" id="' + data.id + '"></li>').appendTo(parent_container);
		if (data.filter_display === true) {
		    self.container.addClass(filter_display);
		}
		self.data = data;
        self.set = set;
        self.index = index_in_set;
		self.playlist = self.set.playlist;
	},

    render: function () {
        var self = this, song_introduction_container;

        self.inputs = {
            singer: jQuery('<input type="text" class="singer" placeholder="singer" value="' + self.playlist.value_or_blank(self.data.singer) + '" />').appendTo(self.container),
            key: jQuery('<input type="text" class="key" placeholder="key" value="' + self.playlist.value_or_blank(self.data.key) + '" />').appendTo(self.container),
            capo: jQuery('<input type="text" class="capo" placeholder="capo" value="' + self.playlist.value_or_blank(self.data.capo) + '" />').appendTo(self.container),
            duration: jQuery('<input type="text" class="duration" placeholder="mm:ss" value="' + self.playlist.value_or_blank(self.data.duration) + '" />').appendTo(self.container)
        };
        jQuery('<span class="title">' + self.playlist.value_or_blank(self.data.title) + '</span>').appendTo(self.container);
        jQuery('<span class="id">(' + self.playlist.value_or_blank(self.data.id) + ')</span>').appendTo(self.container);
        
        self.buttons = {
            lyrics: jQuery('<span class="button lyrics">lyics</span>').appendTo(self.container),
            remove: jQuery('<span class="button remove">remove</span>').appendTo(self.container),
            move: jQuery('<span class="button move">move to...</span>').appendTo(self.container),
            intro_toggle: jQuery('<span class="button toggle-introduction">intro</span>').appendTo(self.container)
        };
        
        song_introduction_container = jQuery('<span class="introduction" style="display: none"></span>').appendTo(self.container);
        self.inputs.introduction = {
            text: jQuery('<textarea class="introduction_text" placeholder="Introduction text">' + self.playlist.value_or_blank(self.data.introduction.text) + '</textarea>').appendTo(song_introduction_container),
            duration: jQuery('<input type="text" class="introduction_duration" placeholder="Introduction duration" value="' + self.playlist.value_or_blank(self.data.introduction.duration) + '" />').appendTo(song_introduction_container)
        };
        
        self.bind_buttons();
    },
    
    get_data: function () {
        var self = this, data;
        
        data = {
            id: self.data.id,
            key: self.inputs.key.val(),
            singer: self.inputs.singer.val(),
            title: self.data.title,
            introduction: {
                duration: self.inputs.introduction.duration.val(),
                text: self.inputs.introduction.text.val()
            },
            capo:  self.inputs.capo.val(),
            duration: self.inputs.duration.val()
        };

        return data;
    },

    bind_buttons: function () {
        var self = this;

        self.buttons.lyrics.click(function () {
            self.playlist.display_song(self);
        });
    }
});