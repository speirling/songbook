SBK.PlayList = SBK.SongList.extend({
	init: function (playlist_name, container) {
		var self = this;

		self.call_super(container, '#jsr-playlist-list');
		self.playlist_name = playlist_name;
		self.fetch_parameters = {action: 'get_playlist', playlist_name: self.playlist_name};
	},

	save_playlist: function (callback) {
		var self = this;

		self.pleasewait.show();
		self.http_request.api_call(
		    {
			    action: 'update_playlist',
			    playlist_name: self.playlist_name,
			    playlist_data: JSON.stringify(self.data_json)
			},
		    function (data) {
				if(typeof(callback) === 'function') {
			    	callback(data);
			    	self.pleasewait.hide();
				}
    		}
		);
	},

	display_content: function (server_data) {
		var self = this;
		
		self.container.html(self.playlist_html);
		self.save_button = jQuery('<a href="#" id="savePlaylist">Save</a>').prependTo(self.container).click(function() {
			self.pleasewait.show();
			self.update();
			self.save_playlist(function () {
				self.pleasewait.hide();
				self.render();
			});
		});
		self.toggle_intro_button = jQuery('<a href="#" id="toggle-introductions">Toggle all Introductions</a>').prependTo(self.container).click(function() {
			self.toggle_introductions();
		});
		self.add_set_button = jQuery('<a href="#" id="add-new-set">Add a new set</a>').prependTo(self.container).click(function() {
			self.add_set();
		});
		self.hide_introductions();
		self.set_up_context_menus();
		jQuery('.playlist', self.container).sortable();
		jQuery('.songlist', self.container).sortable({connectWith: '.songlist'});
	},
	
	set_up_context_menus: function () {
		var self = this;

		jQuery('li li', self.container).contextMenu('context-menu', {
		    'show lyrics': {
		        click: function(element){ 
		        	window.open('?action=displaySong&id=' + element.attr('id').replace('id_', '') + 
	        			'&key=' + escape(element.children('.key').val()) +
	        			'&singer=' + element.children('.singer').val() +
	        			'&capo=' + element.children('.capo').val()
		        	);
		        }
		    },
		    'edit song': {
		        click: function(element){ window.open('?action=editSong&id=' + element.attr('id').replace('id_', '')); }
		    },
		    'remove from playlist': {
		        click: function(element){ element.remove(); }
		    },
		    'toggle introduction': {
		        click: function(element){
		        	jQuery('.introduction', element).toggle();
		        }
		    }
		});
		jQuery('li.set', self.container).contextMenu('context-menu', {
		    'remove from playlist': {
		        click: function(element){ element.remove(); }
		    }
		});
	},

	toggle_introductions: function() {
		var self = this;

		if(jQuery('#toggle-introductions', self.container).hasClass('open')) {
			jQuery('#playlist-holder .introduction', self.container).hide();
			jQuery('#toggle-introductions', self.container).removeClass('open');
		} else {
			jQuery('#playlist-holder .introduction', self.container).show();
			jQuery('#toggle-introductions', self.container).addClass('open');
		}
	},

	add_set: function() {
		var self = this;

		self.data_json.sets[self.data_json.sets.length] = {
			label: ('enter a title for this set'),
			introduction: {duration: '', text: ''},
			songs: []
		};
		self.playlist_html = self.to_html();
		self.display_content();
	}
});