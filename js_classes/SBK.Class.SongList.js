SBK.SongList = SBK.Class.extend({
	init: function (container, jsr_template_selector, exclusion_songlist) {
		var self = this;

		self.container = container;
		self.template = jQuery(jsr_template_selector);
		self.pleasewait = new SBK.PleaseWait(self.container);
		self.http_request = new SBK.HTTPRequest();
		if(typeof(exclusion_songlist) === 'undefined') {
			self.exclusion_list = null;
		} else {
			self.exclusion_list = self.flatten_exclusion_list(exclusion_songlist);
		}
	},
	
	flatten_exclusion_list: function (exclusion_songlist) {
		var self = this, song_index, set_index, set, output_array = [];

		if(typeof(exclusion_songlist.songs) !== 'undefined') {
			for (song_index = 0; song_index < exclusion_songlist.songs.length; song_index = song_index + 1) {
				output_array[output_array.length] = exclusion_songlist.songs[song_index].id;
			}
		}

		if(typeof(exclusion_songlist.sets) !== 'undefined') {
			for (set_index = 0; set_index < exclusion_songlist.sets.length; set_index = set_index + 1) {
				set = exclusion_songlist.sets[set_index];
				for (song_index = 0; song_index < set.songs.length; song_index = song_index + 1) {
					output_array[output_array.length] = set.songs[song_index].id;
				}
			}
		}
		
		return output_array;
	},

	_fetch: function (callback) {
		var self = this;

		self.pleasewait.show();
		self.http_request.api_call(
		    self.fetch_parameters,
		    function (data) {
		    	callback(data);
		    	self.pleasewait.hide();
    		}
		);
	},
	
	fetch: function (callback) {
		var self = this;

		self._fetch(callback); //so that sub classes can make this conditional - e.g. filename not specified
	},

	display_content: function () {
		throw ('not implemented');
	},

	set_up_context_menus: function () {
		throw ('not implemented');
	},

	to_html: function () {
		var self = this;

		return self.template.render(self.data_json);
	},

	update: function () {
		var self = this;

		self.data_json = self.from_html(self.container);
	},

	from_html: function (source) {
		var self = this, set_count, output_json;

		output_json = {};

		output_json.title = jQuery('.playlist-title', source).val();
		output_json.act = jQuery('.act', source).val();
		output_json.introduction = {
			"duration": jQuery('.introduction.songlist .introduction_duration', source).val(),
		    "text": jQuery('.introduction.songlist .introduction_text', source).val()
		};
		output_json.sets = [];
		set_count = 0;
		jQuery('li.set', source).each(function () {
			var this_set = jQuery(this), song_count;

			output_json.sets[set_count] = {
				"label": jQuery('.set-title', this_set).val(),
				"introduction": {
					"duration": jQuery('.introduction.set .introduction_duration', this_set).val(),
					"text": jQuery('.introduction.set .introduction_text', this_set).val()
				},
				"songs": []
			};
			song_count = 0;
			jQuery('li.song', this_set).each(function () {
				var self = jQuery(this);

				output_json.sets[set_count].songs[song_count] = {
					"id": self.attr('id'),
					"key": jQuery('.key', self).val(),
					"singer": jQuery('.singer', self).val(),
					"capo": jQuery('.capo', self).val(),
					"duration": jQuery('.duration', self).val(),
					"title": jQuery('.title', self).html(),
					"introduction": {
						"duration": jQuery('.introduction_duration', self).val(),
						"text": jQuery('.introduction_text', self).val()
					}
				};
				song_count = song_count + 1;
			});
			set_count = set_count +1;
		});
		return output_json;
	},

	render: function (callback) {
		var self = this;

		self.fetch(function(server_data){
			self.process_server_data(server_data);
			if(typeof(callback) === 'function') {
				callback();
			}
		});
	},

	process_server_data: function (server_data) {
		var self = this;

		self.data_json = server_data.data;
		self.filter_songlist_json_before_display();
		self.playlist_html = self.to_html();
		
		self.display_content();
	},
	
	filter_songlist_json_before_display: function () {
		var self = this, song_index, set_index, set, id_index;

		if(self.exclusion_list !== null) {
			if(typeof(self.data_json.songs) !== 'undefined') {
				for (song_index = 0; song_index < self.data_json.songs.length; song_index = song_index + 1) {
					id_index = jQuery.inArray('' + self.data_json.songs[song_index].id, self.exclusion_list);
					if(id_index !== -1) {
						self.data_json.songs.splice(song_index, 1);
						song_index = song_index -1;
					}
				}
			}
			
			if(typeof(self.data_json.sets) !== 'undefined') {
				for (set_index = 0; set_index < self.data_json.sets.length; set_index = set_index + 1) {
					set = self.data_json.sets[set_index];
					for (song_index = 0; song_index < set.songs.length; song_index = song_index + 1) {
						id_index = jQuery.inArray('' + set.songs[song_index].id, self.exclusion_list);
						if(id_index !== -1) {
							set.songs.splice(song_index, 1);
							song_index = song_index -1;
						}
					}
				}
			}
		}
	},

	hide_introductions: function() {
		jQuery('#playlist-holder .introduction', self.container).hide();
		jQuery('#toggle-introductions', self.container).removeClass('open');
	}
});