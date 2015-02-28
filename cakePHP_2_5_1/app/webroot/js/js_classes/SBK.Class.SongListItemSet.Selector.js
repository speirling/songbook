/*global jQuery SBK alert */

SBK.SongListItemSet.Selector = SBK.SongListItemSet.extend({

    render: function () {
        var self = this, introduction_container, song_index, set_ol;

        self.container = jQuery('<li class="set"></li>').appendTo(self.parent_container);
        self.container.append(self.data.label);
        
        set_ol = jQuery('<ol class="songlist"></ol>').appendTo(self.container);
        for (song_index = 0; song_index < self.data.songs.length; song_index = song_index + 1) {
            self.song_objects[song_index] = new SBK.SongListItemSong.Selector(
                set_ol, 
                self,
                song_index,
                self.data.songs[song_index]
            );
            self.song_objects[song_index].render();
        }
    },
    
    get_selected: function () {
        var self = this, song_index, song_selected_data, selected_songs = [];

        for (song_index = 0; song_index < self.song_objects.length; song_index = song_index + 1) {
            song_selected_data = self.song_objects[song_index].get_selected();
            if (song_selected_data !== false) {
                selected_songs.push(song_selected_data);
            }
        }
        
        return selected_songs;
    }
});