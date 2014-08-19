SBK.SongLyricsEdit = SBK.Class.extend({
	init: function (container, id, app) {
		var self = this;

		self.container = container;
		self.id = id;
		self.app = app;
		self.pleasewait = new SBK.PleaseWait(self.container);
		self.http_request = new SBK.HTTPRequest();
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
            self.http_request.api_call(
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
            chord_mode: new SBK.Button(self.container, 'chord-mode', 'Chord mode'),
            lyrics_mode: new SBK.Button(self.container, 'lyrics', 'Lyrics mode')
        };
        self.header_container = jQuery('<div class="song_headings"></div>').appendTo(self.container);
        self.inputs = {
            title: self.make_input(self.header_container, 'title', song_data.title, 'Title'),
            written_by: self.make_input(self.header_container, 'written_by', song_data.written_by, 'Written by'),
            performed_by: self.make_input(self.header_container, 'performed_by', song_data.performed_by, 'Performed by'),
            base_key: self.make_input(self.header_container, 'base_key', song_data.base_key, 'Base key'),
            meta_tags: self.make_input(self.header_container, 'meta_tags', song_data.meta_tags, 'Meta tags')
        };
        self.song_content_container = jQuery('<div class="content"></div>').appendTo(self.container);
        self.inputs.content = self.make_input(self.header_container, 'content', song_data.content, 'Content');
    },
    
    make_input: function (parent_container, id, value, label_text) {
        var self = this, container, input;

        container = jQuery('<span class="' + id + ' input-container"></span>').appendTo(parent_container);
        jQuery('<span class="label">' + label_text + '<span class="separator">: </span></span>').appendTo(container);
        input = jQuery('<pre contentEditable="true" class="input-box" id="' + id + '">' + value + '</pre>').appendTo(container);

        return input;
    },
    
    save_song: function () {
        var self = this;

        self.http_request.api_call(
            'update_song',
            {Song: {
                id: self.id + '',
                title: self.inputs.title.html(),
                written_by: self.inputs.written_by.html(),
                performed_by: self.inputs.performed_by.html(),
                base_key: self.inputs.base_key.html(),
                content: self.inputs.content.html(),
                meta_tags: self.inputs.meta_tags.html()
            }},
            function (response) {
                if (response.success === true) {
                    self.app.display_lyrics(self.id)
                } else {
                    alert('Failed to save song');
                    throw ('update_song requests failed.' );
                }
            }
        );
    }
});