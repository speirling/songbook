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
        var self = this, page_html = '', set_index, song_index, set_ol, ul, input_container_title, input_container_act, internal_navigation_bar, filter_to;

        lines_per_set_title = 2;
        lines_per_song = 1;
        lines_per_page = 88;
        line_count = 0;
        page_counter = 1;

        page_html = page_html + '<div class="playlist list print-page">';
        // ---start of delete when pdf printer is available ----------------------------
        // until I can get a pdf printer that both acknowledges columns (like microsoft pdf printer) _and_ hyperlinks (like chrome save as pdf) I'd better manually create columns
        column_count = 1;
        page_html = page_html + '<table class="manual-column"><tbody><tr>';
        page_html = page_html + '<td class="column_' + column_count + '">';
        // ---end of delete when pdf printer is available ----------------------------
        
        page_html = page_html + '<span class="playlist-title"><label>Playlist: </label>' + self.value_or_blank(data_json.title) + '</span>';
        page_html = page_html + '<span class="playlist-act">(<label>Act: </label>' + self.value_or_blank(data_json.act) + ')</span>';
        line_count = line_count + 2; // playlist title and act name

        page_html = page_html + '<ul>';
        
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

                page_html = page_html + '<li class="set" id="set_' + set_index + '">' + 
                		'<span class="set-title"><label><span>Set: </span></label><span type="text" class="set-title">' + SBK.StaticFunctions.value_or_blank(set_data.label) + '</span></span>';

                page_html = page_html + '<ol class="songlist">';

                if (typeof(set_data.songs) !== 'undefined') {
                    for (song_index = 0; song_index < set_data.songs.length; song_index = song_index + 1) {
                    	line_count = line_count + lines_per_song;

                    	if ((line_count / lines_per_page) > page_counter) {
                    		page_counter = page_counter + 1;
                    		// ------------------------------
                    		page_html = page_html + '</ol></td></tr></tbody></table>'; //end column
                    		// ------------------------------
                    		page_html = page_html + '</ol></div>'; //end page
                    		
                    		column_count = 1;
                            line_count = 0;
                    		page_html = page_html + '<div class="playlist list print-page"><ol class="songlist">'; //start new page
                    		// ------------------------------
                            page_html = page_html + '<table class="manual-column"><tbody><tr>';
                            page_html = page_html + '<td class="column_' + column_count + '"><ol class="songlist">'; //start new column
                    		// ------------------------------
                    	} else if ((column_count === 1) && (line_count > (lines_per_page / 2))) {
                    		column_count = column_count + 1;
                    		page_html = page_html + '</td>'; //end column
                            page_html = page_html + '<td class="column_' + column_count + '"><ol class="songlist">'; //start new column
                    	}
                    	song_data = set_data.songs[song_index];
                    	page_html = page_html + '<li class="song"><span class="title-bar"><span class="key">' + SBK.StaticFunctions.value_or_blank(song_data.key) + '</span>' + 
                        		'<a href="#song_' + SBK.StaticFunctions.value_or_blank(song_data.id) + '" class="title">' + SBK.StaticFunctions.value_or_blank(song_data.title) + '</a>' + 
                        		'</span></li>';                       
                    }
                }
                page_html = page_html + '</ol>';
                page_html = page_html + '</li>';
            }
        } else {
            console.log('no sets');
        }
        // ----------------------------------------------
        page_html = page_html + '</td></tr></tbody></table>'; //end column
        // ----------------------------------------------
        page_html = page_html + '</ul></div>';

        return page_html;
    }
});