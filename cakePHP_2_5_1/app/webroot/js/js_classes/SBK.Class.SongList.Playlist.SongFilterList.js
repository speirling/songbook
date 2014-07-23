SBK.SongFilterList = SBK.SongList.extend({
	init: function (container, exclusion_list, playlist) {
		var self = this;

		self.api_destination = 'get_available_songs';
		self.playlist = playlist;
		self.call_super(container, '', exclusion_list);
		self.fetch_parameters = {search_string: ''};
	},

    to_html: function (data_json) {
        var self = this, set_index, playlist_container, ul, song_index;

        playlist_container = jQuery('<div class="playlist"></div>');
        
        ul = jQuery('<ul></ul>').appendTo(playlist_container);
        song_index = 0;
        for (song_number in data_json.songs) {
            if (data_json.songs.hasOwnProperty (song_number)) {
                new SBK.SongListItemSong.Selector(
                    ul, 
                    {index: 0, playlist: self},
                    song_index,
                    {id: song_number, title: data_json.songs[song_number]}
                ).render();
                song_index = song_index + 1;
            }
        }

        return playlist_container;
    },

	display_content: function (server_data) {
		var self = this, filter_container, count_container, filter_string_input, number_of_songs, onsubmit;
		
		self.filter_form = jQuery('<form id="allsongsearch"></form>').appendTo(self.container);
        filter_container = jQuery('<span class="label">Filter: </span>').appendTo(self.filter_form);
        count_container = jQuery('<span class="label">Number of songs displayed: </span>').appendTo(self.filter_form);
        filter_string_input = jQuery('<input type="text" id="search_string" value="" />').appendTo(filter_container);
	    number_of_songs = jQuery('<span class="number-of-records"></span>').appendTo(count_container);

		console.log(self.data_json);
		number_of_songs.html(self.data_json.songs.length);
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
	}
});