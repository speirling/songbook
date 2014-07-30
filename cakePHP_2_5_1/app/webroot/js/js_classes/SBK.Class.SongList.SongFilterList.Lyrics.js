SBK.SongFilterList.Lyrics = SBK.SongFilterList.extend({

    to_html: function (data_json) {
        var self = this, set_index, playlist_container, ul, song_index;

        playlist_container = jQuery('<div class="playlist song-filter-list"></div>');
        
        ul = jQuery('<ul></ul>').appendTo(playlist_container);
        song_index = 0;
        self.song_objects = [];
        for (song_number in data_json.songs) {
            if (data_json.songs.hasOwnProperty (song_number)) {
                self.song_objects[song_index] = new SBK.SongListItemSong.Lyrics(
                    ul, 
                    {index: 0, playlist: self.playlist},
                    song_index,
                    {id: song_number, title: data_json.songs[song_number]}
                );
                self.song_objects[song_index].render();
                song_index = song_index + 1;
            }
        }

        return playlist_container;
    }
});