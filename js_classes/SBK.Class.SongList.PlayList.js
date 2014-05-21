/*global jQuery SBK alert */

SBK.PlayList = SBK.SongList.extend({
	init: function (playlist_name, container, exclusion_list) {
		var self = this;

		self.call_super(container, self.get_template(), exclusion_list);
		if(typeof(playlist_name) === 'undefined') {
			self.playlist_name = '';
		} else {
			self.playlist_name = playlist_name;
		}
		self.fetch_parameters = {action: 'get_playlist', playlist_name: self.playlist_name};
	},
	
	get_template: function (callback) {
		var self = this;
		
		return '#jsr-playlist-list';
	},

	fetch: function (callback) {
		var self = this;

		if(self.playlist_name !== '') {
			self._fetch(callback); 
		} else {
			callback({data: {
				title: '',
				act: '',
				introduction: {
					duration: '',
					text: ''
				},
				sets:[{
					label: '',
					introduction: {
						duration: '',
						text: ''
					},
					songs: []
				}]
			}});
		}
	},

	get_set_names: function () {
		var self = this;
		
		return self.set_names; //defined in SBK.Class.SongList.js.filter_songlist_json_before_display()
	},
	
	save_playlist: function () {
		var self = this;

		if(self.playlist_name === '') {
			if(self.data_json.title === '') {
				alert('please enter a title for the playlist');
				return;
			} else {
				self.playlist_name = self.data_json.title.replace(/ /g, '_');
				self.fetch_parameters.playlist_name = self.playlist_name;
			}
		}
		if(self.playlist_name === '') {
			alert('please enter a Playlist Name');
			return;
		} else {
			self.pleasewait.show();
			self.http_request.api_call(
			    {
				    action: 'update_playlist',
				    playlist_name: self.playlist_name,
				    playlist_data: JSON.stringify(self.data_json)
				},
			    function () {
					self.render();
				    self.pleasewait.hide();
	    		}
			);
		}
	},

	display_content: function () {
		var self = this;
		
		self.container.html(self.playlist_html);
		// set up buttons on 
		self.save_button = jQuery('<a href="#" id="savePlaylist">Save</a>').prependTo(self.container).click(function() {
			self.pleasewait.show();
			self.update();
			self.save_playlist();
		});
		self.toggle_intro_button = jQuery('<a href="#" id="toggle-introductions">Toggle all Introductions</a>').prependTo(self.container).click(function() {
			self.toggle_introductions();
		});
		self.add_set_button = jQuery('<a href="#" id="add-new-set">Add a new set</a>').prependTo(self.container).click(function() {
			self.add_set();
		});
		self.hide_introductions();
		
		// set up the buttons on each song
		jQuery('li .remove', self.container).click(function(){ jQuery(this).parent().remove(); });
		jQuery('li .toggle-introduction', self.container).click(function(element){
        	jQuery('.introduction', element).toggle();
        });
        
		jQuery('.playlist ul', self.container).sortable();
		jQuery('.songlist', self.container).sortable({connectWith: '.songlist'});
		
		
	},
	
	set_up_context_menus: function () {
		var self = this;
/* conext menu disabled. Moving to buttons (early 2014). */
		jQuery('li .remove', self.container).click(function(){ jQuery(this).parent().remove(); });
		jQuery('li .toggle-introduction', self.container).click(function(element){
        	jQuery('.introduction', element).toggle();
        });
	},

	toggle_introductions: function() {
		var self = this;

		if(jQuery('#toggle-introductions', self.container).hasClass('open')) {
			jQuery('.introduction', self.container).hide();
			jQuery('#toggle-introductions', self.container).removeClass('open');
		} else {
			jQuery('.introduction', self.container).show();
			jQuery('#toggle-introductions', self.container).addClass('open');
		}
	},

	add_set: function() {
		var self = this;

		self.update();
		self.data_json.sets[self.data_json.sets.length] = {
			label: (''),
			introduction: {duration: '', text: ''},
			songs: []
		};
		self.playlist_html = self.to_html();
		self.display_content();
	}
});