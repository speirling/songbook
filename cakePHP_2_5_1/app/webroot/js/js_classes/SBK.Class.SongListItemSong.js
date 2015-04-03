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
        var self = this, title_bar;

        title_bar = jQuery('<tr></tr>').appendTo(jQuery('<tbody></tbody>').appendTo(jQuery('<table class="title-bar"></table>').appendTo(self.container)));
        self.details_bar = jQuery('<div class="details-bar"></div>').appendTo(self.container);
        self.button_bar = jQuery('<div class="button-bar"></div>').appendTo(self.container);

        if (SBK.StaticFunctions.value_or_blank(self.data.key) !== '') {
            jQuery(' <td class="key">' + SBK.StaticFunctions.value_or_blank(self.data.key) + '</td>').appendTo(title_bar);
        } else {
            jQuery(' <td class="key blank">' + SBK.StaticFunctions.value_or_blank(self.data.key) + '</td>').appendTo(title_bar);
        }
        jQuery('<td class="title">' + SBK.StaticFunctions.value_or_blank(self.data.title) + '</td>').appendTo(title_bar);
        lyrics_button_container = jQuery('<td class="lyrics-button-container"></td>').appendTo(title_bar);
        self.inputs = {
            singer: jQuery('<input type="text" class="singer" size="5" placeholder="singer" value="' + SBK.StaticFunctions.value_or_blank(self.data.singer) + '" />').appendTo(self.details_bar),
            key: jQuery('<input type="text" class="key" size="2" placeholder="key" value="' + SBK.StaticFunctions.value_or_blank(self.data.key) + '" />').appendTo(self.details_bar),
            capo: jQuery('<input type="text" class="capo" size="3" placeholder="capo" value="' + SBK.StaticFunctions.value_or_blank(self.data.capo) + '" />').appendTo(self.details_bar),
            duration: jQuery('<input type="text" class="duration" size="3" placeholder="mm:ss" value="' + SBK.StaticFunctions.value_or_blank(self.data.duration) + '" />').appendTo(self.details_bar)
        };
        jQuery('<span class="id">(' + SBK.StaticFunctions.value_or_blank(self.data.id) + ')</span>').appendTo(self.details_bar);
        self.buttons = {
            lyrics: new SBK.Button(lyrics_button_container, 'lyrics', 'lyics', function () {self.playlist.display_song({id: self.data.id, key: self.data.key, capo: self.data.capo, index: self.index, set_index: self.set.index});}),
            remove: new SBK.Button(self.button_bar, 'remove', 'remove', function () {self.playlist.remove_song({id: self.data.id, key: self.data.key, capo: self.data.capo, index: self.index, set_index: self.set.index});}),
            move_up: new SBK.Button(self.button_bar, 'move move-up', 'up', function () {self.playlist.move_song_up_one({id: self.data.id, key: self.data.key, capo: self.data.capo, index: self.index, set_index: self.set.index});}), 
            move_down: new SBK.Button(self.button_bar, 'move move-down', 'down', function () {self.playlist.move_song_down_one({id: self.data.id, key: self.data.key, capo: self.data.capo, index: self.index, set_index: self.set.index});}), 
            move_to: new SBK.Button(self.button_bar, 'move move-to', 'move to...', function () {self.playlist.display_move_song_destination_chooser({id: self.data.id, key: self.data.key, capo: self.data.capo, index: self.index, set_index: self.set.index});}) 
        };

        if (typeof(self.data.introduction) !== 'undefined') {
            self.introduction_container = jQuery('<span class="introduction" style="display: none"></span>').appendTo(self.container);
            self.inputs.introduction = {
                text: jQuery('<textarea class="introduction_text" placeholder="Introduction text">' + SBK.StaticFunctions.value_or_blank(self.data.introduction.text) + '</textarea>').appendTo(self.introduction_container),
                duration: jQuery('<input type="text" class="introduction_duration" placeholder="Introduction duration" value="' + SBK.StaticFunctions.value_or_blank(self.data.introduction.duration) + '" />').appendTo(self.introduction_container)
            };
        }
    },

    get_data: function () {
        var self = this, data;
        
        data = {
            id: self.data.id,
            key: self.inputs.key.val(),
            singer: self.inputs.singer.val(),
            title: self.data.title,
            introduction: {
                duration: '',
                text: ''
            },
            capo:  self.inputs.capo.val(),
            duration: self.inputs.duration.val()
        };

        if (typeof(self.inputs.introduction) !== 'undefined') {
            data.introduction = {
                duration: self.inputs.introduction.duration.val(),
                text: self.inputs.introduction.text.val()
            };
        }

        return data;
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
    },

    show_details: function() {
        var self = this;
     
        self.details_bar.show();
    },

    hide_details: function() {
        var self = this;
     
        self.details_bar.hide();
    },

    show_buttons: function() {
        var self = this;

        self.button_bar.show();
    },

    hide_buttons: function() {
        var self = this;
     
        self.button_bar.hide();
    }
});