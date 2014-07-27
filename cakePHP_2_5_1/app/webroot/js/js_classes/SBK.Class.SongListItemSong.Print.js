/*global jQuery SBK alert */

SBK.SongListItemSongPrint = SBK.SongListItemSong.extend({

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