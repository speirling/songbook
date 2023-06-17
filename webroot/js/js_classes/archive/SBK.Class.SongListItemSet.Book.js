/*global jQuery SBK alert */

SBK.SongListItemSet.Book = SBK.SongListItemSet.extend({
    render: function () {
        var self = this, introduction_container, song_index, set_ol;

        self.container = jQuery('<li class="set"></li>').appendTo(self.parent_container);
        self.container.append('<span class="title set">' + self.data.label + '</span>');
        
        set_ol = jQuery('<ol class="songlist"></ol>').appendTo(self.container);
        for (song_index = 0; song_index < self.data.songs.length; song_index = song_index + 1) {
        	if (typeof(self.data.songs[song_index]) !== 'undefined') {
	            self.song_objects[song_index] = new SBK.SongListItemSong.Book(
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