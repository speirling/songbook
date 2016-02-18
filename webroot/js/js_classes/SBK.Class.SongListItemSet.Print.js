/*global jQuery SBK alert */

SBK.SongListItemSet.Print = SBK.SongListItemSet.extend({

	render: function () {
        var self = this, song_index, set_ol, title_input_holder;

        self.container = jQuery('<li class="set" id="set_' + self.index + '"></li>').appendTo(self.parent_container);
        title_input_holder = jQuery('<span class="set-title"><label><span>Set: </span></label></span>').appendTo(self.container);
        self.title=  jQuery('<span type="text" class="set-title">' + self.playlist.value_or_blank(self.data.label) + '</span>').appendTo(title_input_holder)

        set_ol = jQuery('<ol class="songlist"></ol>').appendTo(self.container);

        if (typeof(self.data.songs) !== 'undefined') {
            for (song_index = 0; song_index < self.data.songs.length; song_index = song_index + 1) {
                self.song_objects[song_index] = new SBK.SongListItemSong.Print(
                    set_ol, 
                    self,
                    song_index,
                    self.data.songs[song_index]
                );
                self.song_objects[song_index].render();
            }
        }
    }
});