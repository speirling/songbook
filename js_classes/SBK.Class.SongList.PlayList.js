SBK.PlayList = SBK.SongList.extend({
	init: function (playlist_name, container) {
		var self = this;

		self.call_super(container, '#jsr-playlist-list');
		self.playlist_name = playlist_name;
	},

	fetch: function (callback) {
		var self = this;

		self.pleasewait.show();
		self.http_request.api_call(
		    {action: 'get_playlist', playlist_name: self.playlist_name},
		    function (data) {
		    	callback(data);
		    	self.pleasewait.hide();
    		}
		);
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
			//self.pleasewait.show();
			self.update();
			self.save_playlist(function () {
				//self.pleasewait.hide();
				self.render();
			});
		});
		self.toggle_intro_button = jQuery('<a href="#" id="toggle-introductions">Toggle all Introductions</a>').prependTo(self.container).click(function() {
			self.toggle_introductions();
		});
		self.hide_introductions();
		self.set_up_context_menus();
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
		if(jQuery('#toggle-introductions', self.container).hasClass('open')) {
			self.hide_introductions();
		} else {
			jQuery('#playlist-holder .introduction', self.container).show();
			jQuery('#toggle-introductions', self.container).addClass('open');
		}
	}
});