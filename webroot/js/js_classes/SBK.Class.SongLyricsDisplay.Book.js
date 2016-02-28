/*global jQuery SBK */

SBK.SongLyricsDisplay.Book = SBK.SongLyricsDisplay.extend({

    render_response: function (response, buttons_displayed, paginated) {
        var self = this, target_key_container, song_data, key_container, capo_container, lines, page_html = '';

        song_data = response.data;
        
        self.base_key = song_data.base_key;
        if (typeof(self.base_key) === 'undefined' || self.base_key === '') {
        	nominal_key = '';
            keyHTML = '<span class="target-key no-base-key"><span class="data">No base key set</span></span>';
        } else {
            if (typeof(self.key) === 'undefined' || self.key === '') {
                nominal_key = self.base_key;
            } else {
                nominal_key = self.key;
            }
            nominal_key = nominal_key.replace('m', '');
            keyHTML = '<span class="target-key"><span class="label">key: </span><span class="data">' + nominal_key + '</span></span>'+
            '<span class="capo"><span class="label">capo: </span><span class="data">' + self.capo + '</span></span>';
        }

        header_HTML = '<div class="page-header">' +
        '<h2 id="song_' + song_data.id + '" class="title">' + song_data.title + '</h2>' +
        '<div class="key">' + keyHTML + '</div>' +
        '<div class="written-by"><span class="data">' + song_data.written_by + '</span></div>' +
        '<div class="performed-by"><label>performed by: </label><span class="data">' + song_data.performed_by + '</span></div>' +
        '<span class="songnumber"><label>Song no. </label><span class="data">' + song_data.id + '</span></span>' +
        '</div>';

        //new SBK.PaginatedHTML(self.container, '.page-header', 'song-page', '.content');
        //instead of using the tabular layout of SBK.PaginatedHTML, maybe just empirically determine the number of <line>s that can fit on a page columned by CSS3
        lines_per_page = 58;
        characters_per_line = 100;
        height_of_line_with_chords = 1;
        columns_class = '';
        //and after that number, insert a new page
        text_to_nodes_conversion_container = jQuery('<div></div>');
        text_to_nodes_conversion_container.html(self.song_content_to_html(song_data.content));
        lines = jQuery('div.line', text_to_nodes_conversion_container);
        
        if(lines.length > (lines_per_page / 2)) {
        	columns_class = 'columns-2'
            characters_per_line = characters_per_line / 2;
        }
        page_html = page_html + '<div class="lyrics-page print-page lyrics-content ">' + header_HTML; //start new page
        page_html = page_html + '<div class="lyrics-panel ' + columns_class + '">'; //start new lyrics panel
        // ---start of delete when pdf printer is available ----------------------------
        // until I can get a pdf printer that both acknowledges columns (like microsoft pdf printer) _and_ hyperlinks (like chrome save as pdf) I'd better manually create columns
        column_count = 1;
        page_html = page_html + '<table class="manual-column"><tbody><tr>';
        page_html = page_html + '<td class="column_' + column_count + '">';
        // ---end of delete when pdf printer is available ----------------------------
        page_counter = 1;
        line_count = 0;
        for (index = 0; index < lines.length; index = index + 1) {
        	line_count = line_count + 1;
        	
        	if(jQuery(lines[index]).text().length > characters_per_line) {
        		line_count = line_count + 1;
        	}
        	if ((line_count / lines_per_page) > page_counter) {
        		page_counter = page_counter + 1;
        		if ((lines.length - (page_counter * lines_per_page)) > (lines_per_page / 2)) {
                	columns_class = 'columns-2'
                    characters_per_line = characters_per_line / 2;
                }
        		// ------------------------------
        		page_html = page_html + '</td></tr></tbody></table>'; //end column
        		// ------------------------------
        		page_html = page_html + '</div>'; //end lyrics panel
        		page_html = page_html + '</div>'; //end page

                column_count = 1;
                line_count = 0;
        		page_html = page_html + '<div class="lyrics-page print-page lyrics-content following-page">' + header_HTML; //start new page
        		page_html = page_html + '<div class="lyrics-panel ' + columns_class + '">'; //start new lyrics panel
        		// ------------------------------
                page_html = page_html + '<table class="manual-column"><tbody><tr>';
                page_html = page_html + '<td class="column_' + column_count + '">'; //start new column
        		// ------------------------------                
        	} else if ((column_count === 1) && (line_count > (lines_per_page / 2))) {
        		column_count = column_count + 1;
        		page_html = page_html + '</td>'; //end column
                page_html = page_html + '<td class="column_' + column_count + '">'; //start new column
        	}
        	page_html = page_html + '<div class="line">' + jQuery(lines[index]).html() + '</div>';
        }

        // ----------------------------------------------
        page_html = page_html + '</td></tr></tbody></table>'; //end column
        // ----------------------------------------------
		page_html = page_html + '</div>'; //end lyrics panel
        page_html = page_html + '</div>'; //end page
console.log(page_html);
        jQuery(page_html).appendTo(self.container);
    }
});