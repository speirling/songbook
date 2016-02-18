SBK.PlayList.Book = SBK.PlayList.extend({

    to_html: function (data_json) {
        var self = this, set_index, flat_list, song_index, playlist_page;

        self.playlist_container = jQuery('<div class="playlist print"></div>');
        playlist_page = jQuery('<div class="playlist-page"></div>').appendTo(self.playlist_container);
        playlist_header = jQuery('<div class="playlist-header"></div>').appendTo(playlist_page);
        jQuery('<span class="playlist-title">' + self.value_or_blank(data_json.title) + '</span>').appendTo(playlist_header);
        jQuery('<span class="playlist-act">' + self.value_or_blank(data_json.act) + '</span>').appendTo(playlist_header);

        self.introduction_container = jQuery('<span class="introduction songlist" style="display: none"></span>').appendTo(playlist_page);
        
        jQuery('<span class="introduction_text" placeholder="Introduction text">' + self.value_or_blank(data_json.introduction.text) + '</span>').appendTo(self.introduction_container);
        jQuery('<span class="introduction_duration" placeholder="Introduction duration" value="' + self.value_or_blank(data_json.introduction.duration) + '"></span>').appendTo(self.introduction_container);
        
        self.playlist_ul = jQuery('<ul></ul>').appendTo(playlist_page);

        if (typeof(data_json.sets) !== 'undefined') { //could happen when a playlist is first defined
            data_json.sets = [].concat(data_json.sets); //same issue as WORKAROUND below
            for (set_index = 0; set_index < data_json.sets.length; set_index = set_index + 1) {
	            /* WORKAROUND:: simplexml converts a single song into an object, not an array. make sure data_json.sets[set_index].songs is an array!!! */
	            data_json.sets[set_index].songs = [].concat(data_json.sets[set_index].songs);
	            
	            self.set_objects[set_index] = new SBK.SongListItemSet.Print(self.playlist_ul, self, set_index, data_json.sets[set_index]);
	            self.set_objects[set_index].render();
	        }
	        
	        //sort songs numerically
	        flat_list = [];
	        for (set_index = 0; set_index < data_json.sets.length; set_index = set_index + 1) {
	            /* WORKAROUND:: simplexml converts a single song into an object, not an array. make sure data_json.sets[set_index].songs is an array!!! */
	            data_json.sets[set_index].songs = [].concat(data_json.sets[set_index].songs);
	            
	            for (song_index = 0; song_index < data_json.sets[set_index].songs.length; song_index = song_index + 1) {
	                flat_list.push(data_json.sets[set_index].songs[song_index]);
	            }
	        }
	        new SBK.PaginatedHTML(playlist_page, '.playlist-header', 'playlist-page');
	
	        flat_list.sort(function (a, b) {
	            return (a.id - b.id);
	        });
	
	        for (song_index = 0; song_index < flat_list.length; song_index = song_index + 1) {
	            new SBK.SongLyricsDisplay(
	                    jQuery('<div class="lyrics-panel"></div>').appendTo(self.playlist_container), 
	                    flat_list[song_index].id,
	                    self.app,
	                    flat_list[song_index].key, 
	                    flat_list[song_index].capo
	                ).render(null, false, true);  //callback, buttons_displayed, paginated
	        }
        } else {
            console.log('no sets');
        }
       
        
        return self.playlist_container;
    },

    display_content: function () {
        var self = this, button_bar;
    
        self.container.html('');
        self.container.append(self.to_html(self.data_json));

        self.hide_introductions();
    }
});