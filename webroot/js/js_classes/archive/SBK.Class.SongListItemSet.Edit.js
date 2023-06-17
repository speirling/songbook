/*global jQuery SBK alert */

SBK.SongListItemSet.Edit = SBK.SongListItemSet.extend({

    render: function () {
        var self = this, song_index, set_ol, title_input_holder;

        self.container = jQuery('<li class="set" id="set_' + self.index + '"></li>').appendTo(self.parent_container);
        self.button_bar = jQuery('<div class="button-bar"></div>').appendTo(self.container);
        title_input_holder = jQuery('<span class="set-title"><label><span>Set: </span></label></span>').appendTo(self.container);
        self.inputs = {
           title: jQuery('<input type="text" class="set-title" placeholder="set title" value="' + self.playlist.value_or_blank(self.data.label) + '" />').appendTo(title_input_holder)
        };
        jQuery('<span class="duration"></span>').appendTo(self.container);
        
        self.buttons = {
            toggle_buttons: new SBK.Button(title_input_holder, 'toggle-buttons', '<span>&dArr;<span>', function () {self.toggle_buttons();}),
            remove: new SBK.Button(self.button_bar, 'remove', 'remove this set', function () {self.playlist.remove_set({set_index: self.index});}),
            add_song: new SBK.Button(self.button_bar, 'add', 'add songs to this set', function () {self.playlist.display_song_picker(self.index);})
        };

        self.introduction_container = jQuery('<span class="introduction set" style="display: none"></span>').appendTo(self.container);
        if (typeof(self.data.introduction) !== 'undefined') {
            self.inputs.introduction = {
                text: jQuery('<textarea class="introduction_text" placeholder="Introduction text">' + self.playlist.value_or_blank(self.data.introduction.text) + '</textarea>').appendTo(self.introduction_container),
                duration: jQuery('<input type="text" class="introduction_duration" placeholder="Introduction duration" value="' + self.playlist.value_or_blank(self.data.introduction.duration) + '" />').appendTo(self.introduction_container)
            };
        }
        set_ol = jQuery('<ol class="songlist"></ol>').appendTo(self.container);

        if (typeof(self.data.songs) !== 'undefined') {
            for (song_index = 0; song_index < self.data.songs.length; song_index = song_index + 1) {
                self.song_objects[song_index] = new SBK.SongListItemSong.Edit(
                    set_ol, 
                    self,
                    song_index,
                    self.data.songs[song_index]
                );
                self.song_objects[song_index].render();
            }
        }
        self.playlist.make_draggable(set_ol, 'td.title, td.key', 'ol.songlist');
    },

    toggle_buttons: function() {
        var self = this;

        if (self.button_bar.hasClass('open')) {
            self.button_bar.hide();
            self.button_bar.removeClass('open');
        } else {
            self.button_bar.show();
            self.button_bar.addClass('open');
        }
    },

    hide_introductions: function() {
        var self = this, song_index;

        if(typeof(self.introduction_container) !== 'undefined') {
            self.introduction_container.hide();
        }

        for (song_index = 0; song_index < self.song_objects.length; song_index = song_index + 1) {
            self.song_objects[song_index].hide_introductions();
        }
    },

    show_introductions: function() {
        var self = this, song_index;
     
        self.introduction_container.show();

        for (song_index = 0; song_index < self.song_objects.length; song_index = song_index + 1) {
            self.song_objects[song_index].show_introductions();
        }
    },

    show_details: function() {
        var self = this, song_index;

        for (song_index = 0; song_index < self.song_objects.length; song_index = song_index + 1) {
            self.song_objects[song_index].show_details();
        }
    },

    hide_details: function() {
        var self = this, song_index;

        for (song_index = 0; song_index < self.song_objects.length; song_index = song_index + 1) {
            self.song_objects[song_index].hide_details();
        }
    },

    show_edit_buttons: function() {
        var self = this, song_index;
        
        self.button_bar.show();

        for (song_index = 0; song_index < self.song_objects.length; song_index = song_index + 1) {
            self.song_objects[song_index].show_buttons();
        }
    },

    hide_edit_buttons: function() {
        var self = this, song_index;

        self.button_bar.hide();

        for (song_index = 0; song_index < self.song_objects.length; song_index = song_index + 1) {
            self.song_objects[song_index].hide_buttons();
        }
    }
});