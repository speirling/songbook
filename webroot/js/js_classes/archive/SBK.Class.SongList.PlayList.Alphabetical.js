/*global jQuery SBK alert */

SBK.PlayList.Alphabetical = SBK.PlayList.extend({

    to_html: function (data_json) {
        var self = this, set_index, song_index, ul, input_container_title, input_container_act, internal_navigation_bar, filter_to;

        self.playlist_container = jQuery('<div class="playlist sorted-alphabetically"></div>');
        input_container_title = jQuery('<span class="playlist-title"><label>Playlist: </label><span class="playlist-title">' + self.value_or_blank(data_json.title) + '</span></span>').appendTo(self.playlist_container);
        input_container_act = jQuery('<span class="playlist-act"><label>Act: </label><span class="act">' + self.value_or_blank(data_json.act) + '</span></span>').appendTo(self.playlist_container);
        self.introduction_container = {
            hide: function () {}
        };
        
        self.playlist_ul = jQuery('<ul></ul>').appendTo(self.playlist_container);
        songs = [];
        self.song_objects = [];
        if (typeof(data_json.sets) !== 'undefined') { //could happen when a playlist is first defined
            data_json.sets = [].concat(data_json.sets); //same issue as WORKAROUND below
            for (set_index = 0; set_index < data_json.sets.length; set_index = set_index + 1) {
                /* WORKAROUND:: simplexml converts a single song into an object, not an array. make sure data_json.sets[set_index].songs is an array!!! */
                /* problem with this if there are NO songs */
                if (typeof(data_json.sets[set_index].songs) !== 'undefined') {
                    if (typeof(data_json.sets[set_index].songs) !== 'undefined') {
                       songs =  songs.concat(data_json.sets[set_index].songs);
                    }
                }
            }

            songs.sort(function(a, b){return a.title.localeCompare(b.title);});
            for (song_index = 0; song_index < songs.length; song_index = song_index + 1) {

                jQuery('<li class="song" id="' + songs[song_index].id + '"><table class="title-bar"><tbody><tr><td class="singer">' + SBK.StaticFunctions.value_or_blank(songs[song_index].singer) + '</td><td class="key">' + SBK.StaticFunctions.value_or_blank(songs[song_index].key) + '</td><td class="title">' + SBK.StaticFunctions.value_or_blank(songs[song_index].title) + '</td></tr></tbody></table></li>').appendTo(self.playlist_ul);                    
            }
        } else {
            console.log('no sets');
        }

        return self.playlist_container;
    },

    insert_buttons: function (button_bar) {
        var self = this;

        return {
            close: new SBK.Button(button_bar, 'close', '&laquo; Playlists', function () {self.app.display_playlist_list();}),
            all_songs: new SBK.Button(button_bar, 'all-songs', '&laquo; All songs', function () {self.app.list_all_songs();}),
            sets: new SBK.Button(button_bar, 'show-sets', 'Sets', function () {self.app.display_playlist();})
        }
    }
});