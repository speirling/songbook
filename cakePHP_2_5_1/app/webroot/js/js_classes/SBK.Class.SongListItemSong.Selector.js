/*global jQuery SBK alert */

SBK.SongListItemSong.Selector = SBK.SongListItemSong.extend({

    render: function () {
        var self = this, song_introduction_container;

        jQuery('<span class="checkbox"></span>').appendTo(self.container);
        jQuery('<span class="title">' + self.playlist.value_or_blank(self.data.title) + '</span>').appendTo(self.container);
        jQuery('<span class="id">(' + self.playlist.value_or_blank(self.data.id) + ')</span>').appendTo(self.container);
        if(typeof(self.data.singer) !== 'undefined' && self.data.singer !== '') {
            jQuery('<span class="singer">(' + self.playlist.value_or_blank(self.data.singer) + ')</span>').appendTo(self.container);
        }
        if(typeof(self.data.key) !== 'undefined' && self.data.key !== '') {
            jQuery('<span class="key">(' + self.playlist.value_or_blank(self.data.key) + ')</span>').appendTo(self.container);
        }
        self.container.bind('click', function () {
            self.container.toggleClass('selected');
        })
    },

    get_selected: function () {
        var self = this;
        
        if (self.container.hasClass('selected')) {
            return self.data;
        } else {
            return false;
        }
    },

    set_selected: function () {
        var self = this, data;
        
        self.container.addClass('selected');
    }
});