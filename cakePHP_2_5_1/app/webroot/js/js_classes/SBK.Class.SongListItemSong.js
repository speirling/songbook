/*global jQuery SBK alert */

SBK.SongListItemSong = SBK.Class.extend({
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
        var self = this, button_bar;

        self.inputs = {
            singer: jQuery('<input type="text" class="singer" placeholder="singer" value="' + self.playlist.value_or_blank(self.data.singer) + '" />').appendTo(self.container),
            key: jQuery('<input type="text" class="key" placeholder="key" value="' + self.playlist.value_or_blank(self.data.key) + '" />').appendTo(self.container),
            capo: jQuery('<input type="text" class="capo" placeholder="capo" value="' + self.playlist.value_or_blank(self.data.capo) + '" />').appendTo(self.container),
            duration: jQuery('<input type="text" class="duration" placeholder="mm:ss" value="' + self.playlist.value_or_blank(self.data.duration) + '" />').appendTo(self.container)
        };
        jQuery('<span class="title">' + self.playlist.value_or_blank(self.data.title) + '</span>').appendTo(self.container);
        jQuery('<span class="id">(' + self.playlist.value_or_blank(self.data.id) + ')</span>').appendTo(self.container);
        button_bar = jQuery('<span class="button-bar"></span>').appendTo(self.container);
        self.buttons = {
            lyrics: jQuery('<span class="button lyrics">lyics</span>').appendTo(button_bar),
            remove: jQuery('<span class="button remove">remove</span>').appendTo(button_bar),
            move: jQuery('<span class="button move">move to...</span>').appendTo(button_bar),
            //intro_toggle: jQuery('<span class="button toggle-introduction">intro</span>').appendTo(self.container)
        };
        if (typeof(self.data.introduction) !== 'undefined') {
            self.introduction_container = jQuery('<span class="introduction" style="display: none"></span>').appendTo(self.container);
            self.inputs.introduction = {
                text: jQuery('<textarea class="introduction_text" placeholder="Introduction text">' + self.playlist.value_or_blank(self.data.introduction.text) + '</textarea>').appendTo(self.introduction_container),
                duration: jQuery('<input type="text" class="introduction_duration" placeholder="Introduction duration" value="' + self.playlist.value_or_blank(self.data.introduction.duration) + '" />').appendTo(self.introduction_container)
            };
        }

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
            self.playlist.display_song({id: self.data.id, key: self.data.key, capo: self.data.capo, index: self.index, set_index: self.set.index});
        });
        self.buttons.remove.click(function () {
            self.playlist.remove_song({id: self.data.id, key: self.data.key, capo: self.data.capo, index: self.index, set_index: self.set.index});
        });
    },

    hide_introductions: function() {
        var self = this;

        if(typeof(self.introduction_container) !== 'undefined') {
            self.introduction_container.hide();
        }
    },

    show_introductions: function() {
        var self = this;
     
        self.introduction_container.show();
    }
});