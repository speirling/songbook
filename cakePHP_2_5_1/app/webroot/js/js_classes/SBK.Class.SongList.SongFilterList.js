SBK.SongFilterList = SBK.SongList.extend({
	init: function (container, app, exclusion_list, playlist) {
		var self = this;

		self.api_destination = 'get_available_songs';
		self.playlist = playlist;
		self.call_super(container, app, '', exclusion_list);
		self.fetch_parameters = {search_string: ''};
		self.app = app;
	},

    to_html: function (data_json) {
        var self = this, set_index, playlist_container, ul, song_index;

        playlist_container = jQuery('<div class="playlist song-filter-list"></div>');
        
        ul = jQuery('<ul></ul>').appendTo(playlist_container);
        song_index = 0;
        self.song_objects = [];
        for (song_number in data_json.songs) {
            if (data_json.songs.hasOwnProperty (song_number)) {
                self.song_objects[song_index] = new SBK.SongListItemSong.Selector(
                    ul, 
                    {index: 0, playlist: self},
                    song_index,
                    {id: song_number, title: data_json.songs[song_number]}
                );
                self.song_objects[song_index].render();
                song_index = song_index + 1;
            }
        }

        return playlist_container;
    },

	display_content: function (server_data) {
		var self = this, search_container, count_container, filter_string_input, onsubmit, filter_to, filter_border, filter_wrapper;
		
		self.container.html('');
		button_bar = jQuery('<span class="button-bar"></span>').appendTo(self.container);
        self.buttons = {
                all_playlists: new SBK.Button(button_bar, 'all-songs', 'List all Playists', function () {self.app.display_playlist_list();}),
                add_new_song: new SBK.Button(button_bar, 'add-new-song', 'Add a new Song', function () {self.app.application_state.set({
                    tab: 'add_new_song',
                    playlist_filename: null,
                    id: null,
                    key: null,
                    capo: null
                }, false);}),
                full_text_search: new SBK.Button(button_bar, 'full-text-search', 'Full-text search', function () {self.search_form.toggle();})
            };
		self.search_form = jQuery('<form id="allsongsearch"></form>').appendTo(self.container);
        search_container = jQuery('<span class="full-text-search"><label>Full-text search: </label></span>').appendTo(self.search_form);
        filter_string_input = jQuery('<input type="text" id="search_string"  placeholder="full-text search" value="" />').appendTo(search_container);
        self.search_form.hide();

        self.filter = jQuery('<div class="picker-filter"></div>').appendTo(self.container);
        filter_border = jQuery('<div class="picker-filter-border"></div>').appendTo(self.filter);
        filter_wrapper = jQuery('<div class="picker-filter-wrapper"></div>').appendTo(filter_border);
        self.filter_input = jQuery('<input type="text" placeholder="type to filter"/>').appendTo(filter_wrapper);
        self.filter_clear = jQuery('<span class="icon-close"></span>').appendTo(filter_border);
        count_container = jQuery('<span class="number"><label> songs displayed</label></span>').appendTo(self.filter);
        self.number_of_songs = jQuery('<span class="number-of-records"></span>').prependTo(count_container);
        
        self.filter_input.unbind('keyup').keyup(function () {
            if (filter_to) {
                clearTimeout(filter_to);
            }
            filter_to = setTimeout(function () {
                filter_value = self.filter_input.val();
                self.filter_song_list(filter_value);
            }, 500);
        });

        self.filter_clear.bind('click', function () {
            self.filter_input.val('');
            self.filter_song_list('');
        });

		filter_string_input.val(self.fetch_parameters.search_string);
		
		self.container.append(self.to_html(self.data_json));
		
		onsubmit = jQuery.proxy(self.on_search_form_submit, self);
		self.search_form.bind('submit', onsubmit);
        self.number_of_songs.html(jQuery('li.song:visible', self.container).length);
	},
    
    filter_song_list: function (filter_value) {
        var self = this, current_li, show_this_li, song_title;

        jQuery('li.song', self.container).each(function () {
            current_li = jQuery(this);
            song_title = jQuery('.title', current_li).html();
            show_this_li = song_title.toLowerCase().indexOf(filter_value.toLowerCase()) !== -1;
            current_li.toggle(show_this_li);
        });
        self.number_of_songs.html(jQuery('li.song:visible', self.container).length);
    },

	on_search_form_submit: function () {
		var self = this, value;
		
		value = jQuery('#search_string', self.search_form).val();
		self.fetch_parameters.search_string = value;
		self.render();
		
		return false;
	},
	
	filter_by_songlist: function (songlist) {
		console.log(songlist);
	},
    
    get_selected: function () {
        var self = this, song_index, selected_songs = [];

        for (song_index = 0; song_index < self.song_objects.length; song_index = song_index + 1) {
            song_data = self.song_objects[song_index].get_selected();

            if (song_data !== false) {
                selected_songs.push(song_data);
            }
        }
        
        return selected_songs;
    }
});