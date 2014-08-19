/*global jQuery SBK alert */

SBK.SongListItemSong.Lyrics = SBK.SongListItemSong.extend({

    render: function () {
        var self = this, button_bar;

        jQuery('<span class="title">' + SBK.StaticFunctions.value_or_blank(self.data.title) + '</span>').appendTo(self.container);
        jQuery('<span class="id">(' + SBK.StaticFunctions.value_or_blank(self.data.id) + ')</span>').appendTo(self.container);
        button_bar = jQuery('<span class="button-bar"></span>').appendTo(self.container);
        new SBK.Button(button_bar, 'lyrics', 'lyics', function() {self.playlist.display_song({id: self.data.id, key: self.data.key, capo: self.data.capo, index: self.index, set_index: self.set.index});});
    }
});