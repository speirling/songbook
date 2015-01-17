SBK.SongLyricsEdit = SBK.Class.extend({
	init: function (container, id, app) {
		var self = this;

		self.container = container;
        self.id = id;
		self.app = app;
		self.pleasewait = new SBK.PleaseWait(self.container);
		self.api = app.api;
		self.chord_editor = new SBK.ChordEditor(self.container, function (chord_string, range) {
		    if(range !== null) {
                range.deleteContents();
                if(chord_string === '') {
                    range.insertNode(document.createTextNode(''));
                } else {
                    range.insertNode(document.createTextNode('[' + chord_string + ']'));
                }
		    }
		});
		self.chord_editor.render();
	},
    
    render: function () {
        var self = this, all_playlists;

        self.container.html('');
        if(self.id === null) {
            self.render_response({
                data: {
                    Song: {
                        id: null,
                        title: '',
                        written_by: '',
                        performed_by: '',
                        base_key: '',
                        content: '',
                        meta_tags: ''
                    }
                }
            });
        } else {
            self.pleasewait.show();
            self.api.api_call(
                'get_song',
                {id: self.id},
                function (response) {
                    self.render_response(response);
                    self.pleasewait.hide();
                }
            );
        }
    },
    
    render_response: function (response) {
        var self = this, target_key_container;

        song_data = response.data.Song;

        self.buttons = {
            save: new SBK.Button(self.container, 'save', 'Save', function () {self.save_song();}),
            cancel: new SBK.Button(self.container, 'cancel', 'Cancel', function () {self.app.back();}),
            chord_mode: new SBK.Button(self.container, 'chord-mode', 'Chord mode', function () {self.enter_add_chords_mode();})
        };
        self.header_container = jQuery('<div class="song-headings"></div>').appendTo(self.container);
        self.inputs = {
            title: self.make_input(self.header_container, 'title', song_data.title, 'Title'),
            written_by: self.make_input(self.header_container, 'written_by', song_data.written_by, 'Written by'),
            performed_by: self.make_input(self.header_container, 'performed_by', song_data.performed_by, 'Performed by'),
            base_key: self.make_input(self.header_container, 'base_key', song_data.base_key, 'Base key'),
            meta_tags: self.make_input(self.header_container, 'meta_tags', song_data.meta_tags, 'Meta tags')
        };
        self.song_content_container = jQuery('<div class="content"></div>').appendTo(self.container);
        self.inputs.content = self.make_textarea(self.song_content_container, 'content', song_data.content, 'Content');
    },
    
    make_input: function (parent_container, id, value, label_text) {
        var self = this, container, input;

        container = jQuery('<span class="' + id + ' input-container"></span>').appendTo(parent_container);
        jQuery('<span class="label">' + label_text + '<span class="separator">: </span></span>').appendTo(container);
        //input = jQuery('<pre contentEditable="true" class="input-box" id="' + id + '">' + value + '</pre>').appendTo(container); //NEEDS .text() in save_song
        input = jQuery('<input type="text" class="input-box" id="' + id + '" value="' + value + '" />').appendTo(container);

        return input;
    },
    
    make_textarea: function (parent_container, id, value, label_text) {
        var self = this, container, input, window_container;

        container = jQuery('<span class="' + id + ' input-container"></span>').appendTo(parent_container);
        jQuery('<span class="label">' + label_text + '<span class="separator">: </span></span>').appendTo(container);
        //input = jQuery('<pre contentEditable="true" class="input-box" id="' + id + '">' + value + '</pre>').appendTo(container);  //NEEDS .text() in save_song
        input = jQuery('<textarea class="input-box" id="' + id + '">' + value + '</textarea>').appendTo(container);
        window_container = parent_container.parent().parent();
        console.log(input.offset(), parent_container.offset(), parent_container.width(), parent_container.height(), window_container.offset().top);
        input.width(parent_container.width() - (input.offset().left + parent_container.offset().left));
        input.height(window_container.height() - (input.offset().top + window_container.offset().top));

        return input;
    },
    
    save_song: function () {
        var self = this;

        //self.inputs.content.html(self.inputs.content.html().replace(/<br\s*[\/]?>/gi, "\n"));

        if (self.id === null) {

            self.api.api_call(
                'update_song',
                {Song: {
                    title: self.inputs.title.val(),
                    written_by: self.inputs.written_by.val(),
                    performed_by: self.inputs.performed_by.val(),
                    base_key: self.inputs.base_key.val(),
                    content: self.inputs.content.val(),
                    meta_tags: self.inputs.meta_tags.val()
                }},
                function (response) {
                    if (response.success !== false) { // could be a number
                        self.id = response.data.id;
                        self.app.display_song({id: self.id, key: null, capo: null});
                    } else {
                        alert('Failed to save new song. ' + response.success);
                        throw ('"add new song" request failed. ' + response.success );
                    }
                }
            );
        } else {
            self.api.api_call(
                'update_song',
                {Song: {
                    id: self.id + '',
                    title: self.inputs.title.val(),
                    written_by: self.inputs.written_by.val(),
                    performed_by: self.inputs.performed_by.val(),
                    base_key: self.inputs.base_key.val(),
                    content: self.inputs.content.val(),
                    meta_tags: self.inputs.meta_tags.val()
                }},
                function (response) {
                    if (response.success !== false) { // could be a number
                        self.app.display_song({id: self.id, key: null, capo: null});
                    } else {
                        alert('Failed to update existing song. ' + response.success);
                        throw ('update_song request failed. ' + response.success );
                    }
                }
            );
        }
    },

    enter_add_chords_mode: function () {
        var self = this, caret_position, chord_text = '', key = false, selectionObject, current_value;

        //self.inputs.title.parent().hide();
        self.inputs.written_by.parent().hide();
        self.inputs.performed_by.parent().hide();
        self.inputs.base_key.parent().hide();
        self.inputs.meta_tags.parent().hide();

        if(self.chord_editor.display_mode === 'static') {
            self.chord_editor.container.show();
        }
        self.inputs.content.bind('click', function (event) {
            self.chord_editor.open(window.getSelection(), event.pageX, event.pageY);
        });
        self.buttons.chord_mode.set_text('exit chord mode');
        self.buttons.chord_mode.click(function () {self.exit_add_chords_mode();});
        self.buttons.chord_mode.addClass('chord-mode-active');
    },

    exit_add_chords_mode: function () {
        var self = this;

        self.inputs.title.parent().show();
        self.inputs.written_by.parent().show();
        self.inputs.performed_by.parent().show();
        self.inputs.base_key.parent().show();
        self.inputs.meta_tags.parent().show();

        if(self.chord_editor.display_mode === 'static') {
            self.chord_editor.container.hide();
        }
        self.inputs.content.unbind('keypress').unbind('click');
        self.buttons.chord_mode.set_text('Chord mode');
        self.buttons.chord_mode.click(function () {self.enter_add_chords_mode();});
        self.buttons.chord_mode.removeClass('chord-mode-active');
    }
});