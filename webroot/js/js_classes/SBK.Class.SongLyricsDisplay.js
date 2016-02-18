/*global jQuery SBK */

SBK.SongLyricsDisplay = SBK.Class.extend({
	init: function (container, id, app, key, capo, on_close) {
		var self = this;

		self.container = container;
		self.id = id;
		self.app = app;
		self.key = key;
		if(typeof(capo) === 'undefined') {
		    self.capo = 0;
		} else {
		    self.capo = capo;
		}
		self.pleasewait = new SBK.PleaseWait(self.container);
		self.api = app.api;
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
        var self = this, target_key_container, song_data, key_container, capo_container;

        if (typeof(buttons_displayed) === 'undefined') {
            buttons_displayed = true;
        }
        if (typeof(paginated) === 'undefined') {
            paginated = false;
        }
        song_data = response.data;
        self.base_key = song_data.base_key;
        if (buttons_displayed) {
            button_bar = jQuery('<span class="button-bar"></span>').appendTo(self.container);
            self.buttons = {
                edit: new SBK.Button(button_bar, 'edit', 'Edit', function () {self.app.edit_song(self.id);}), 
                close: new SBK.Button(button_bar, 'close', 'Close', function () {self.close();}),
                zoom_in: new SBK.Button(button_bar, 'zoom_in', '&nbsp;&nbsp;-&nbsp;&nbsp;', function () {self.zoom_content_in();}),
                zoom_out: new SBK.Button(button_bar, 'zoom_out', '&nbsp;&nbsp;+&nbsp;&nbsp;', function () {self.zoom_content_out();}),
                toggle_chords: new SBK.Button(button_bar, 'toggle_chords', 'toggle chords', function () {self.toggle_chords();})
            };
        }

        self.header_container = jQuery('<div class="page-header"></div>').appendTo(self.container);
        jQuery('<h2 id="song_' + song_data.id + '" class="title">' + song_data.title + '</h2>').appendTo(self.header_container);
        jQuery('<span class="pagenumber"><span class="label">page</span><span id="page_number" class="data">' + '</span><span class="label">of</span><span id="number_of_pages" class="data">' + '</span></span>').appendTo(self.header_container).hide();
        jQuery('<span class="songnumber"><span class="label">Song no. </span><span class="data">' + song_data.id + '</span></span>').appendTo(self.header_container);
        key_container = jQuery('<div class="key"></div>').appendTo(self.header_container);
        jQuery('<div class="written-by"><span class="data">' + song_data.written_by + '</span></div>').appendTo(self.header_container);
        if(song_data.performed_by) {
        	jQuery('<div class="performed-by"><span class="label">performed by: </span><span class="data">' + song_data.performed_by + '</span></div>').appendTo(self.header_container);
        }
        
        target_key_container = jQuery('<span class="target-key"></span>').appendTo(key_container);
        if (typeof(self.base_key) === 'undefined' || self.base_key === '') {
            jQuery('<span class="no-base-key">No base key set</span>').appendTo(target_key_container);
        } else {
            if (typeof(self.key) === 'undefined' || self.key === '') {
                nominal_key = self.base_key;
            } else {
                nominal_key = self.key;
            }
            nominal_key = nominal_key.replace('m', '');
            capo_container = jQuery('<span class="capo"></span>').appendTo(key_container);
            jQuery('<span class="label">key: </span>').appendTo(target_key_container);
            jQuery('<select class="data">' +
                    '<option value=""></option>' + 
                    '<option value="Ab">Ab</option>' + 
                    '<option value="A">A</option>' +
                    '<option value="A#">A#</option>' +
                    '<option value="Bb">Bb</option>' +
                    '<option value="B">B</option>' +
                    '<option value="C">C</option>' +
                    '<option value="C#">C#</option>' +
                    '<option value="Db">Db</option>' +
                    '<option value="D">D</option>' +
                    '<option value="D#">D#</option>' +
                    '<option value="Eb">Eb</option>' +
                    '<option value="E">E</option>' +
                    '<option value="F">F</option>' +
                    '<option value="F#">F#</option>' +
                    '<option value="Gb">Gb</option>' +
                    '<option value="G">G</option>' +
                    '<option value="G#">G#</option>' +
                    '</select>').val(nominal_key).change(function (){self.app.application_state.set({key: jQuery(this).val()});}).appendTo(target_key_container);
            jQuery('<span class="label">capo: </span>').appendTo(capo_container);
            jQuery('<select class="data">' +
                    '<option value="0">0</option>' + 
                    '<option value="1">1</option>' +
                    '<option value="2">2</option>' +
                    '<option value="3">3</option>' +
                    '<option value="4">4</option>' +
                    '<option value="5">5</option>' +
                    '<option value="6">6</option>' +
                    '<option value="7">7</option>' +
                    '<option value="8">8</option>' +
                    '</select>').val(self.capo).change(function (){self.app.application_state.set({capo: jQuery(this).val()});}).appendTo(capo_container);
        }
        self.song_content_container = jQuery('<div class="lyrics-content"></div>').appendTo(self.container);
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
        var self = this;
        
        return SBK.StaticFunctions.LyricChordHTML.song_content_to_html(content_response, self.base_key, self.key, self.capo);
    },
    
    zoom_content_in: function () {
        var self = this;

        if (self.container.hasClass('zoom-8')) {
            self.container.removeClass('zoom-8');
            self.container.addClass('zoom-7');
        } else if (self.container.hasClass('zoom-7')) {
            self.container.removeClass('zoom-7');
            self.container.addClass('zoom-6');
        } else if (self.container.hasClass('zoom-6')) {
            self.container.removeClass('zoom-6');
            self.container.addClass('zoom-5');
        } else if (self.container.hasClass('zoom-5')) {
            self.container.removeClass('zoom-5');
            self.container.addClass('zoom-4');
        } else if (self.container.hasClass('zoom-4')) {
            self.container.removeClass('zoom-4');
            self.container.addClass('zoom-3');
        } else if (self.container.hasClass('zoom-3')) {
            self.container.removeClass('zoom-3');
            self.container.addClass('zoom-2');
        } else if (self.container.hasClass('zoom-2')) {
            self.container.removeClass('zoom-2');
            self.container.addClass('zoom-1');
        }
    },
    
    zoom_content_out: function () {
        var self = this;

        if (self.container.hasClass('zoom-1')) {
            self.container.removeClass('zoom-1');
            self.container.addClass('zoom-2');
        } else if (self.container.hasClass('zoom-2')) {
            self.container.removeClass('zoom-2');
            self.container.addClass('zoom-3');
        } else if (self.container.hasClass('zoom-3')) {
            self.container.removeClass('zoom-3');
            self.container.addClass('zoom-4');
        } else if (self.container.hasClass('zoom-4')) {
            self.container.removeClass('zoom-4');
            self.container.addClass('zoom-5');
        } else if (self.container.hasClass('zoom-5')) {
            self.container.removeClass('zoom-5');
            self.container.addClass('zoom-6');
        } else if (self.container.hasClass('zoom-6')) {
            self.container.removeClass('zoom-6');
            self.container.addClass('zoom-7');
        } else if (self.container.hasClass('zoom-7')) {
            self.container.removeClass('zoom-7');
            self.container.addClass('zoom-8');
        }
    },
    
    toggle_chords: function () {
        var self = this;

        if (self.container.hasClass('chords-hidden')) {
            self.container.removeClass('chords-hidden');
        } else {
            self.container.addClass('chords-hidden');
        }
    },

    close: function () {
    	var self = this;
 
    	self.container.remove();
    	if(typeof(self.on_close) === 'function') {
		    self.on_close();
		}
    }
});