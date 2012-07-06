SBK.SongList = SBK.Class.extend({
	init: function (container, jsr_template_selector) {
		var self = this;

		self.container = container;
		self.template = jQuery(jsr_template_selector);
		self.pleasewait = new SBK.PleaseWait(self.container);
		self.http_request = new SBK.HTTPRequest();
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

	display_content: function (server_data) {
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

		self.fetch(function(data_json_string){
			self.process_server_data(data_json_string);
			if(typeof(callback) === 'function') {
				callback();
			}
		});
	},

	process_server_data: function (server_data) {
		var self = this;

		self.data_json = server_data.data;
		self.playlist_html = self.to_html();
		
		self.display_content();
	},

	hide_introductions: function() {
		jQuery('#playlist-holder .introduction', self.container).hide();
		jQuery('#toggle-introductions', self.container).removeClass('open');
	}
});