SBK.SongFilterList = SBK.SongList.extend({
	init: function (container, app, exclusion_list, playlist) {
		var self = this;

		self.api_destination = 'get_available_songs';
		self.playlist = playlist;
		self.call_super(container, app, '', exclusion_list);
		self.fetch_parameters = {search_string: ''};
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
		var self = this, filter_container, count_container, filter_string_input, number_of_songs, onsubmit;
		
		self.container.html('');
		self.filter_form = jQuery('<form id="allsongsearch"></form>').appendTo(self.container);
        filter_container = jQuery('<span class="filter"><label>Filter: </label></span>').appendTo(self.filter_form);
        count_container = jQuery('<span class="number"><label> songs displayed</label></span>').appendTo(self.filter_form);
        filter_string_input = jQuery('<input type="text" id="search_string" value="" />').appendTo(filter_container);
	    number_of_songs = jQuery('<span class="number-of-records"></span>').prependTo(count_container);

		number_of_songs.html(Object.keys(self.data_json.songs).length);
		filter_string_input.val(self.fetch_parameters.search_string);
		
		self.container.append(self.to_html(self.data_json));
		
		onsubmit = jQuery.proxy(self.on_filter_form_submit, self);
		self.filter_form.bind('submit', onsubmit);
	},

	on_filter_form_submit: function () {
		var self = this, value;
		
		value = jQuery('#search_string', self.filter_form).val();
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