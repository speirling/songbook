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

        title_bar = jQuery('<span class="title-bar"></span>').appendTo(self.container);

        if (SBK.StaticFunctions.value_or_blank(self.data.key) !== '') {
            jQuery(' <span class="key">' + SBK.StaticFunctions.value_or_blank(self.data.key) + '</span>').appendTo(title_bar);
        } else {
            jQuery(' <span class="key blank">' + SBK.StaticFunctions.value_or_blank(self.data.key) + '</span>').appendTo(title_bar);
        }
        jQuery('<span class="title">' + SBK.StaticFunctions.value_or_blank(self.data.title) + '</span>').appendTo(title_bar);

        jQuery('<span class="id">(' + SBK.StaticFunctions.value_or_blank(self.data.id) + ')</span>').appendTo(self.details_bar);
        self.container.bind('click', function () {self.playlist.display_song({id: self.data.id, key: self.data.key, capo: self.data.capo, index: self.index, set_index: self.set.index});});
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

    toggle_buttons_and_details: function() {
        var self = this;

        if (self.details_bar.hasClass('open')) {
            self.hide_details();
            self.hide_buttons();
            self.details_bar.removeClass('open');
        } else {
            self.show_details();
            self.show_buttons();
            self.details_bar.addClass('open');
        }
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