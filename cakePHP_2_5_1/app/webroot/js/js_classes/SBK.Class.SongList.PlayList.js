/*global jQuery SBK alert */

SBK.PlayList = SBK.SongList.extend({
	init: function (playlist_name, container, exclusion_list, app) {
		var self = this;

		self.call_super(container, '', exclusion_list);
		if(typeof(playlist_name) === 'undefined') {
			self.playlist_name = '';
		} else {
			self.playlist_name = playlist_name;
		}
		self.api_destination = 'get_playlist';
		self.fetch_parameters = {playlist_name: self.playlist_name};
		self.set_objects = [];
		if (typeof(app) === 'undefined') {
            self.app = null;
		} else {
		    self.app = app;
		}
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
		
		return self.set_names; //defined in SBK.Class.SongList.js  filter_songlist_json_before_display()
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

    to_html: function () {
        var self = this, set_index;

        playlist_container = jQuery('<div class="playlist"></div>');
        self.inputs = {
            title: jQuery('<input type="text" class="playlist-title" placeholder="playlist title" value="' + self.value_or_blank(self.data_json.title) + '" />').appendTo(playlist_container),
            act: jQuery('<input type="text" class="act" placeholder="act" value="' + self.value_or_blank(self.data_json.act) + '" />').appendTo(playlist_container)
        };
        playlist_introduction_container = jQuery('<span class="introduction songlist" style="display: none"></span>').appendTo(playlist_container);
        self.inputs.introduction = {
            text: jQuery('<textarea class="introduction_text" placeholder="Introduction text">' + self.value_or_blank(self.data_json.introduction.text) + '</textarea>').appendTo(playlist_introduction_container),
            duration: jQuery('<input type="text" class="introduction_duration" placeholder="Introduction duration" value="' + self.value_or_blank(self.data_json.introduction.duration) + '" />').appendTo(playlist_introduction_container)
        };
        
        ul = jQuery('<ul></ul>').appendTo(playlist_container);
     
        for (set_index = 0; set_index < self.data_json.sets.length; set_index = set_index + 1) {
            self.set_objects[set_index] = new SBK.SongListItemSet(ul, self, set_index, self.data_json.sets[set_index]);
            self.set_objects[set_index].render();
        }
       
        return playlist_container;
    },

	display_content: function () {
		var self = this;
	
		self.container.html('');
		self.container.append(self.playlist_html);

		// set up buttons on 
		self.save_button = jQuery('<a id="savePlaylist">Save</a>').prependTo(self.container).click(function() {
			self.pleasewait.show();
			self.update();
			self.save_playlist();
		});
		self.toggle_intro_button = jQuery('<a id="toggle-introductions">Toggle all Introductions</a>').prependTo(self.container).click(function() {
			self.toggle_introductions();
		});
		self.add_set_button = jQuery('<a id="add-new-set">Add a new set</a>').prependTo(self.container).click(function() {
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
		
		calculated_data = self.get_data();
	},
	
	set_up_context_menus: function () {
		var self = this;

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
		console.log(self.playlist_html);
		self.display_content();
	},
    
    get_data: function () {
        var self = this, set_index, data;

        data = {
            title: self.inputs.title.val(),
            act: self.inputs.act.val(),
            introduction: {
                duration: self.inputs.introduction.duration.val(),
                text: self.inputs.introduction.text.val()
            },
            sets:[]
        };

        for (set_index = 0; set_index < self.set_objects.length; set_index = set_index + 1) {
            data.sets[set_index] = self.set_objects[set_index].get_data();
        }
        
        return data;
    },
    
    value_or_blank: function (value) {
        var self = this;
        
        if (typeof(value) === 'string') {
            result = value;
        } else {
            result = '';
        }
        
        return result;
    },
    
    display_song: function (song) {
        var self = this;
        
        if (self.app !== null) {
            self.app.display_song(song);
        }
    },
    
    get_length: function (set_index) {
        var self = this, next_set;

        return self.data.sets.length;
    },
    
    get_next_set: function (set_index) {
        var self = this, next_set;

        if (set_index === self.data.sets.length - 1) {
            next_set = null;
        } else {
            next_set = self.data.sets[index + 1];
        }
        
        return next_set;
    },
    
    get_previous_set: function (set_index) {
        var self = this, previous_set;

        if (set_index === 0) {
            previous_set = null;
        } else {
            previous_set = self.data.sets[index - 1];
        }
        
        return previous_set;
    }
});