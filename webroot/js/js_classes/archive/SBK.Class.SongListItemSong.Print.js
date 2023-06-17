/*global jQuery SBK alert */

SBK.SongListItemSong.Print = SBK.SongListItemSong.extend({

    render: function () {
        var self = this, title_bar;

        title_bar = jQuery('<span class="title-bar"></span>').appendTo(self.container);

        if (SBK.StaticFunctions.value_or_blank(self.data.key) !== '') {
            jQuery(' <span class="key">' + SBK.StaticFunctions.value_or_blank(self.data.key) + '</span>').appendTo(title_bar);
        } else {
            jQuery(' <span class="key blank">' + SBK.StaticFunctions.value_or_blank(self.data.key) + '</span>').appendTo(title_bar);
        }
        jQuery('<span class="title"><a href="#song_' + self.data.id + '">' + SBK.StaticFunctions.value_or_blank(self.data.title) + '</a></span>').appendTo(title_bar);

        jQuery('<span class="id">(' + SBK.StaticFunctions.value_or_blank(self.data.id) + ')</span>').appendTo(self.details_bar);
   }
});