SBK.PlayList.Book = SBK.PlayList.extend({

    display_content: function () {
        var self = this, playlist_page;
    
        self.container.html('');
        playlist_page = self.to_html(self.data_json);
        //new SBK.PaginatedHTML(playlist_page, '.playlist-header', 'playlist-page');
        self.container.html(playlist_page);

        //sort songs numerically
        flat_list = [];
        for (set_index = 0; set_index < self.data_json.sets.length; set_index = set_index + 1) {
            /* WORKAROUND:: simplexml converts a single song into an object, not an array. make sure data_json.sets[set_index].songs is an array!!! */
        	self.data_json.sets[set_index].songs = [].concat(self.data_json.sets[set_index].songs);
            
            for (song_index = 0; song_index < self.data_json.sets[set_index].songs.length; song_index = song_index + 1) {
                flat_list.push(self.data_json.sets[set_index].songs[song_index]);
            }
        }

        flat_list.sort(function (a, b) {
            return (a.id - b.id);
        });

        for (song_index = 0; song_index < flat_list.length; song_index = song_index + 1) {
        	if (typeof(flat_list[song_index]) !== 'undefined') {
        		new SBK.SongLyricsDisplay.Book(
                    jQuery('<div class="song"></div>').appendTo(self.container), 
                    flat_list[song_index].id,
                    self.app,
                    flat_list[song_index].key, 
                    flat_list[song_index].capo
                ).render(null, false, true);  //callback, buttons_displayed, paginated
        	}
        }

        //self.hide_introductions();
    },

    to_html: function (data_json) {
        var self = this, playlist_HTML, set_index, song_index, set_ol, ul, input_container_title, input_container_act, internal_navigation_bar, filter_to;

        playlist_HTML = '<div class="playlist list print-page">';
        playlist_HTML = playlist_HTML + '<span class="playlist-title"><label>Playlist: </label>' + self.value_or_blank(data_json.title) + '</span>';
        playlist_HTML = playlist_HTML + '<span class="playlist-act"><label>Act: </label>' + self.value_or_blank(data_json.act) + '</span>';
        playlist_HTML = playlist_HTML + '<ul>';

        lines_per_set_title = 2;
        lines_per_song = 1;
        lines_per_page = 88;
        line_count = 0;
        page_counter = 1;
        
        if (typeof(data_json.sets) !== 'undefined') { //could happen when a playlist is first defined
            data_json.sets = [].concat(data_json.sets); //same issue as WORKAROUND below
            for (set_index = 0; set_index < data_json.sets.length; set_index = set_index + 1) {
            	line_count = line_count + lines_per_set_title;
            	set_data = data_json.sets[set_index];
                /* WORKAROUND:: simplexml converts a single song into an object, not an array. make sure data_json.sets[set_index].songs is an array!!! */
                /* problem with this if there are NO songs */
                if (typeof(data_json.sets[set_index].songs) !== 'undefined') {
                    data_json.sets[set_index].songs = [].concat(data_json.sets[set_index].songs);
                }

                playlist_HTML = playlist_HTML + '<li class="set" id="set_' + set_index + '">' + 
                		'<span class="set-title"><label><span>Set: </span></label><span type="text" class="set-title">' + SBK.StaticFunctions.value_or_blank(set_data.label) + '</span></span>';

                playlist_HTML = playlist_HTML + '<ol class="songlist">';

                if (typeof(set_data.songs) !== 'undefined') {
                    for (song_index = 0; song_index < set_data.songs.length; song_index = song_index + 1) {
                    	line_count = line_count + lines_per_song;
                    	if ((line_count / lines_per_page) > page_counter) {
                    		page_counter = page_counter + 1;
                    		playlist_HTML = playlist_HTML + '</ul></div><div class="playlist list print-page"><ul>';
                    	}
                    	song_data = set_data.songs[song_index];
                    	playlist_HTML = playlist_HTML + '<li class="song"><span class="title-bar"><span class="key">' + SBK.StaticFunctions.value_or_blank(song_data.key) + '</span>' + 
                        		'<a href="#song_' + SBK.StaticFunctions.value_or_blank(song_data.id) + '" class="title">' + SBK.StaticFunctions.value_or_blank(song_data.title) + '</a>' + 
                        		'</span></li>';                       
                    }
                }
                playlist_HTML = playlist_HTML + '</ol>';
                playlist_HTML = playlist_HTML + '</li>';
            }
        } else {
            console.log('no sets');
        }
        playlist_HTML = playlist_HTML + '</ul></div>';

        return playlist_HTML;
    }
});