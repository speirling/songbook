/*global jQuery SBK alert */

SBK.SongListItemSong.Selector = SBK.SongListItemSong.extend({

    render: function () {
        var self = this, song_introduction_container;

        self.inputs = {
            checkbox: jQuery('<input type="checkbox"></input>').appendTo(self.container)
        };
        jQuery('<span class="title">' + self.playlist.value_or_blank(self.data.title) + '</span>').appendTo(self.container);
        jQuery('<span class="id">(' + self.playlist.value_or_blank(self.data.id) + ')</span>').appendTo(self.container);
        if(typeof(self.data.singer) !== 'undefined' && self.data.singer !== '') {
            jQuery('<span class="singer">(' + self.playlist.value_or_blank(self.data.singer) + ')</span>').appendTo(self.container);
        }
        if(typeof(self.data.key) !== 'undefined' && self.data.key !== '') {
            jQuery('<span class="key">(' + self.playlist.value_or_blank(self.data.key) + ')</span>').appendTo(self.container);
        }
    },

    get_selected: function () {
        var self = this;
        
        if (self.inputs.checkbox.is(':checked')) {
            return self.data;
        } else {
            return false;
        }
    },

    set_selected: function () {
        var self = this, data;
        
        self.inputs.checkbox.prop("checked", true);
    }
});