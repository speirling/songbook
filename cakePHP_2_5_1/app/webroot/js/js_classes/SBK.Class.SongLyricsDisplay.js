/*global jQuery SBK */

SBK.SongLyricsDisplay = SBK.Class.extend({
	init: function (container, id, app, key, capo, on_close) {
		var self = this;

		self.container = container;
		self.id = id;
		self.app = app;
		self.key = key;
		self.capo = capo;
		self.pleasewait = new SBK.PleaseWait(self.container);
		self.api = new SBK.Api();
		if(typeof(on_close) === 'function') {
		    self.on_close = on_close;
		} else {
			self.on_close = null;
		}
	},
    
    render: function (callback, buttons_displayed, paginated) {
        var self = this;

        self.container.html('');
        self.pleasewait.show();
        self.api.api_call(
            'get_song',
            {id: self.id},
            function (response) {
                self.song = self.render_response(response, buttons_displayed, paginated);
                if(typeof(callback) === 'function') {
                    callback(self.song);
                }
                self.pleasewait.hide();
            },
            function (response) {
                self.render_error_response(response);
            }
        );
    },
    
    render_response: function (response, buttons_displayed, paginated) {
        var self = this, target_key_container, song_data, key_container;

        if (typeof(buttons_displayed) === 'undefined') {
            buttons_displayed = true;
        }
        if (typeof(paginated) === 'undefined') {
            paginated = false;
        }
        song_data = response.data.Song;
        self.base_key = song_data.base_key;
        if (buttons_displayed) {
            button_bar = jQuery('<span class="button-bar"></span>').appendTo(self.container);
            self.buttons = {
                edit: new SBK.Button(button_bar, 'edit', 'Edit', function () {self.app.edit_song(self.id);}), 
                close: new SBK.Button(button_bar, 'close', 'Close', function () {self.close();})
            };
        }

        self.header_container = jQuery('<div class="page-header"></div>').appendTo(self.container);
        jQuery('<h2 id="song_' + song_data.id + '" class="title">' + song_data.title + '</h2>').appendTo(self.header_container);
        jQuery('<span class="songnumber"><span class="label">Song no. </span><span class="data">' + song_data.id + '</span></span>').appendTo(self.header_container);
        jQuery('<span class="pagenumber"><span class="label">page</span><span id="page_number" class="data">' + '</span><span class="label">of</span><span id="number_of_pages" class="data">' + '</span></span>').appendTo(self.header_container).hide();
        jQuery('<div class="written_by"><span class="data">' + song_data.written_by + '</span></div>').appendTo(self.header_container);
        jQuery('<div class="performed_by"><span class="label">performed by: </span><span class="data">' + song_data.performed_by + '</span></div>').appendTo(self.header_container);
        key_container = jQuery('<div class="key"></div>').appendTo(self.header_container);
        target_key_container = jQuery('<div class="target_key"></div>').appendTo(key_container);
        jQuery('<span class="label">key: </span>').appendTo(target_key_container);
        jQuery('<span class="data"></span>').appendTo(target_key_container);
        self.song_content_container = jQuery('<div class="content"></div>').appendTo(self.container);
        self.song_content_container.html(self.song_content_to_html(song_data.content));


        if (paginated) {
            new SBK.PaginatedHTML(self.container, '.page-header', 'song-page', '.content');
        }
    },
    
    render_error_response: function (response) {
        var self = this, button_bar;
        
        button_bar = jQuery('<span class="button-bar"></span>').appendTo(self.container);
        self.buttons = {
            close: new SBK.Button(button_bar, 'close', 'Close', function () {self.close();})
        };
        self.header_container = jQuery('<div class="page-header"></div>').appendTo(self.container);
        jQuery('<h2 class="title">Error: ' + response.data + '</h2>').appendTo(self.header_container);

    },
    
    song_content_to_html: function (content_response) {
        var self = this, html;

        html = content_response.replace(/&([^#n])/g, '&#38;$1');

        html = html.replace(/\n/g,'</span></div><div class="line"><span class="text">');
        //empty lines - put in a non-breaking space so that they don't collapse?
        html = html.replace(/<div class="line"><span class="text">[\s]*?<\/span><\/div>/g, '<div class="line"><span class="text">&nbsp;</span></div>');
        // chords that are close together - [Am][D] etc ... even [Am]  [D].... should be separated by characters equal in width to the chord
        //I'll mark these kinds of chords with "!" so that I can set their class in  sbk_chord_replace_callback
        // "\h" matches a 'horizontal whitespace' character so the expression '/\](\h*?)\[/' should find relevant chords
        html = html.replace(/\](\h*?)\[/g, '!]$1[');
        html = html.replace(/\[(.*?)\]/g, self.chord_replace_callback);
        html = html.replace(/#<span class="chord">([^<]*?)\/([^<]*?)<\/span>#/g,'<span class="chord">$1<span class="bass_note_modifier separator">/</span><span class="bass_note_modifier note">$2</span></span>');
        html = html.replace(/&nbsp;/g, '&#160;'); //&nbsp; doesn't work in XML unless it's specifically declared.
        html = '<div class="line"><span class="text">' + html;
        
        return html;
    },
    
    chord_replace_callback: function (match) {
        var self = this, chord, fullsize_class = '';

        chord = match;
        chord = chord.replace('[', '');
        chord = chord.replace(']', '');

        if(chord.indexOf('!') > -1) {
            //This is one of those chords followed by whitepace, that needs to be set to greater than 0 space.
            //I'll use a class for that
            fullsize_class = " full-width";
            chord = chord.replace('!', '');
        }
        if(typeof(self.base_key) !== 'undefined' && typeof(self.key) !== 'undefined' && self.base_key !== '' && self.key !== '') {
            chord = SBK.StaticFunctions.transpose_chord(chord, self.base_key, self._key);
        }

        return '</span><span class="chord' + fullsize_class + '">' + chord + '</span><span class="text">';
    },

    close: function () {
    	var self = this;
 
    	self.container.remove();
    	if(typeof(self.on_close) === 'function') {
		    self.on_close();
		}
    }
});