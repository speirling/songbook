$(document).ready(function() {

	jQuery('#playlist-holder').each(function () {
		var self = jQuery(this), playlist;

		playlist = new SBK.playlist(self.attr('filename'), self);
		playlist.render(function () {
			jQuery('.playlist', self.container).sortable();
			jQuery('.songlist', self.container).sortable({connectWith: '.songlist'});
		});
	});

	jQuery('#available-songs').each(function () {
		var self = jQuery(this), playlist;

		available_song_list = new SBK.SongSelectorList(self);
		available_song_list.render(function () {
			jQuery('.songlist', self.container).sortable({connectWith: '#playlist-holder .songlist'});
		});
	});

	//create_filter_list(jQuery('#available-songs'));

	jQuery('#add-new-set').click(function () {
		add_new_setlist(jQuery('#playlist-holder>ul'));
	});

	jQuery('.song-index .song').click(function () {
		 window.open('?action=displaySong&id=' + jQuery(this).attr('id'));
	});
	jQuery('a#remove_linebreaks').click(function (){
		jQuery('textarea#content').html(jQuery('textarea#content').html().replace(/\n\n/gm, "\n"));
	});
	jQuery('a#add_chords').click(sbk_enter_add_chords_mode);

	jQuery('.playlist-chooser').change(function () {
		var value = jQuery(this).val(), container = jQuery('#available-songs');
		
		if (value === 'all') {
			create_filter_list(container);
		} else {
			display_songpicker_from_playlist(container, value);
		}
	});
});

//===================================================================
//namespace
var SBK = {};
//===================================================================
//http://ejohn.org/blog/simple-javascript-inheritance/
//Inspired by base2 and Prototype
(SBK.Class = function () {
 var initializing = false, fnTest = /xyz/.test(function () { xyz; }) ? /\bcall_super\b/ : /.*/, Class;

 // The base Class implementation (does nothing)
 Class = function () {};
 
 function copy_property(name, fn, call_super) {
     return function () {
         var tmp, ret;
         tmp = this.call_super;

         // Add a new .call_super() method that is the same method
         // but on the super-class
         this.call_super = call_super[name];

         // The method only need to be bound temporarily, so we
         // remove it when we're done executing
         ret = fn.apply(this, arguments);
         this.call_super = tmp;
        
         return ret;
     };
 }

 function copy_properties(prop, new_prototype, call_super) {
     var name;
     
     for (name in prop) {
         // Check if we're overwriting an existing function
         if (typeof prop[name] === "function" && typeof call_super[name] === "function" && fnTest.test(prop[name])) {
             new_prototype[name] = copy_property(name, prop[name], call_super);
         } else {
             new_prototype[name] = prop[name];
         }
     }
 }

 // Create a new Class that inherits from this class
 Class.extend = function (prop) {
     var call_super, new_prototype;
     call_super = this.prototype;

     // Instantiate a base class (but only create the instance,
     // don't run the init constructor)
     initializing = true;
     new_prototype = new this();
     initializing = false;

     // Copy the properties over onto the new prototype
     copy_properties(prop, new_prototype, call_super);

     // The dummy class constructor
     function Class() {
         // All construction is actually done in the init method
         if (!initializing && this.init) {
             this.init.apply(this, arguments);
         }
     }

     // Populate our constructed prototype object
     Class.prototype = new_prototype;
    
     // Enforce the constructor to be what we expect
     Class.constructor = Class;

     // And make this class extendable
     Class.extend = arguments.callee;
    
     return Class;
 };
 
 return Class;
}());
//===================================================================
SBK.PleaseWait = SBK.Class.extend({
	init: function (parent_container) {
		var self = this;

		self.container = jQuery('<div class="pleasewait">Please wait...</div>').appendTo(parent_container);
		self.hide();
	},
	
	show: function () {
		var self = this;

		self.container.show();
	},
	
	hide: function () {
		var self = this;

		self.container.hide();
	}
});
//===================================================================
SBK.HTTPRequest = SBK.Class.extend({
	init: function (container) {
		var self = this;

		self.endpoint = '/songbook/api.php';
	},

    make_post_request: function (url, data, success, failure) {
        var self = this;

        try {
            jQuery.ajax({
                cache: true,
                data: data,
                dataType: 'json',
                type: 'post',
                url: url,
                success: function (server_data, text_status, xhr) {
                    // jQuery treats 0 as success, but this is what some browsers return when the XHR failed due to a
                    // network error
                    if (xhr.status === 0) {
                        throw "Network Error";
                    } else {
                    	if(server_data === null) {
                    		throw('The server returned Null');
                    	}
                    	if(server_data.success === true) {
                    		success(server_data);
                    	} else {
                    		throw('Got a response from server, but the data indicated an error');
                    	}
                    }
                },
                error: function (xhr, text_status, error_thrown) {
                    throw('post to [' + url + '] gave an error condition : [' + error_thrown +']');
                }
            });
        } catch (e) {
            //
        }
    },
    
    api_call: function (data, success, failure) {
        var self = this;

        return self.make_post_request(self.endpoint, data, success, failure);
    }
});
//===================================================================
SBK.songlist = SBK.Class.extend({
	init: function (container, jsr_template_selector) {
		var self = this;

		self.container = container;
		self.template = jQuery(jsr_template_selector);
		self.pleasewait = new SBK.PleaseWait(self.container);
		self.http_request = new SBK.HTTPRequest();
	},
	
	fetch: function () {
		throw ('not implemented');
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

		self.data_json = self.from_html(self.container.html());
	},

	from_html: function (html) {
		var self = this, set_count, output_json;

		source = jQuery('<div>' + html + '</div>');
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
        self.songlist = jQuery('.songlist', self.container);
        self.playlist = jQuery('.playlist', self.container);
	},

	update: function () {
		var self = this;

		self.data_json = self.from_html(self.container.html());
	},

	hide_introductions: function() {
		jQuery('#playlist-holder .introduction', self.container).hide();
		jQuery('#toggle-introductions', self.container).removeClass('open');
	}
});//===================================================================
SBK.playlist = SBK.songlist.extend({
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
		console.log(jQuery('ul.playlist', self.container));
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
//===================================================================
SBK.SongSelectorList = SBK.songlist.extend({
	init: function (container) {
		var self = this;

		self.call_super(container, '#jsr-song-selector-list');
	},
	
	fetch: function (callback) {
		var self = this;
		
		self.pleasewait.show();
		self.http_request.api_call(
		    {action: 'get_available_songs'},
		    function (data) {
		    	callback(data);
		    	self.pleasewait.hide();
    		}
		);
	},

	display_content: function (server_data) {
		var self = this, search_form_html;
		
		search_form_html = '<form id="allsongsearch">' +
        '<span class="label">Filter: </span><input type="test" id="search_string" value="" />' + 
        '<span class="label">Number of songs displayed: </span><span class="number-of-records"></span>' + 
        '</form>';
		self.container.html(search_form_html + self.playlist_html);
		self.hide_introductions();
		self.set_up_context_menus();
	},

	set_up_context_menus: function () {
		var self = this;

		jQuery('ul', self.container).sortable();
		jQuery('ul ol', self.container).sortable({
			connectWith: '.playlist ol'
		});
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
		    }
		});
	}
});
//===================================================================
function display_playlist_editor () {
	jQuery('#playlist-holder').each(function () {
		var self = jQuery(this), playlist;
		
		playlist = self.attr('filename');
		jQuery.get(
		    '/songbook/display_playlist.php',
		    {playlist: playlist},
		    function (data) {
		    	self.html(data);
        		jQuery('ul', self).sortable();
        		jQuery('ul ol', self).sortable({
        			connectWith: '.playlist ol'
        		});
        		jQuery('li li', self).contextMenu('context-menu', {
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
        		jQuery('li.set', self).contextMenu('context-menu', {
        		    'remove from playlist': {
        		        click: function(element){ element.remove(); }
        		    }
        		});
        		jQuery('#savePlaylist').unbind('click').click(function() {
        			save_playlist();
        		});
        		hide_introductions();
        		jQuery('#toggle-introductions').unbind('click').click(function() {
        			toggle_introductions();
        		});
    		}
		);
	});
}

function add_new_setlist(container) {
	var newSet = jQuery('<li class="set playlist"><textarea class="set-title" type="text">New Set</textarea></li>').contextMenu('context-menu', {
	    'remove from playlist': {
	        click: function(element){ element.remove(); }
	    }
	});
	var newList = jQuery('<ul class="ui-sortable"><li class=dummy>&nbsp;</li></ul>').sortable({
			connectWith: '.playlist ol'
	});
	container.append(newSet);
	newSet.append(newList);
}

function search_allsongs() {
	jQuery.get(
	    '/songbook/allsongs_filterlist.php',
	    {search_string: jQuery('#search_string').val()},
	    function (data) {
	    	jQuery('div#all-song-list').html(data);
	    	jQuery('#allsongsearch .number-of-records').html(jQuery('div#all-song-list .numberofrecords').html());
	    	if(jQuery('.displayPlaylist').length) {
        		jQuery('#playlist-holder ul ul, #allsongs ul').sortable({
        			connectWith: '.playlist ol'
        		});
    		}
    		if(jQuery('#allsongs').length) {
        		jQuery('#allsongs li').contextMenu('context-menu', {
        		    'show lyrics': {
        		        click: function(element){ window.open('?action=displaySong&id=' + element.attr('id').replace('id_', '')); }
        		    },
        		    'edit song': {
        		        click: function(element){ window.open('?action=editSong&id=' + element.attr('id').replace('id_', '')); }
        		    },
        		    'remove from playlist': {
        		        click: function(element){ element.remove(); }
        		    }
        		});
        		jQuery('#allsongs li').click(function(){ window.open('?action=displaySong&id=' + jQuery(this).attr('id').replace('id_', '')); });
    		}
	    }
	);
}

function save_playlist() {
	playlist = jQuery('#playlist-holder');
	playlist_json = convert_playlist_to_json (playlist);
	jQuery.post(
	    '/songbook/update_playlist.php',
	    {
	    	filename: playlist.attr('filename'),
	    	data: JSON.stringify(playlist_json)
	    },
	    function (response) {
	    	try {
	    		data = JSON.parse(response);
	    		if(data.success) {
	    			//console.log('playlist saved to ' + data.destination);
	    			display_playlist_editor ();
	    		}
	    	} catch (e) {
	    		throw('Playlist Save error : ' + response);
	    	}
	    }
	);
}

function toggle_introductions() {
	if(jQuery('#toggle-introductions').hasClass('open')) {
		hide_introductions();
	} else {
		jQuery('#playlist-holder .introduction').show();
		jQuery('#toggle-introductions').addClass('open');
	}
}

function hide_introductions() {
	jQuery('#playlist-holder .introduction').hide();
	jQuery('#toggle-introductions').removeClass('open');
}

function convert_playlist_to_json (source) {
	var set_count, output_json;
	//source is the jQuery(container) holding the EDITED playlist
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
}

function create_filter_list(container) {
	var html = '<form id="allsongsearch">' +
               '<span class="label">Filter: </span><input type="test" id="search_string" value="" />' + 
               '<span class="label">Number of songs displayed: </span><span class="number-of-records"></span>' + 
               '</form>' +
               '<div id="all-song-list"><span class="pleasewait">please wait...</span></div>' +
               '</div>';
	container.html(html);
	search_allsongs();	
	jQuery('form#allsongsearch').submit(function () {
		search_allsongs();
		return false;
    });
}

function display_songpicker_from_playlist(parent_container, playlist) {
	var container = jQuery('<div id="all-song-list"></div>');
	parent_container.html('');
	parent_container.append(container);
	jQuery.get(
		    '/songbook/display_playlist.php',
		    {playlist: playlist},
		    function (data) {
		    	container.html(data);
		    	if(jQuery('.displayPlaylist').length) {
	        		jQuery('ol', container).sortable({
	        			connectWith: '.playlist ol'
	        		});
	    		}
	    		if(jQuery('li', container).length) {
	        		jQuery('li', container).contextMenu('context-menu', {
	        		    'show lyrics': {
	        		        click: function(element){ window.open('?action=displaySong&id=' + element.attr('id').replace('id_', '')); }
	        		    },
	        		    'edit song': {
	        		        click: function(element){ window.open('?action=editSong&id=' + element.attr('id').replace('id_', '')); }
	        		    },
	        		    'remove from playlist': {
	        		        click: function(element){ element.remove(); }
	        		    }
	        		});
	    		}
		    }
		);
}

function sbk_charCode_to_key_text(char_code) {
	var codes = {
		'65': 'A', //A
		'66': 'B', //B
		'67': 'C', //C
		'68': 'D', //D
		'69': 'E',
		'70': 'F',
		'71': 'G',
		
		'97': 'A', //a
		'98': 'B', //b
		'99': 'C', //c
		'100': 'D', //d
		'101': 'E',
		'102': 'F',
		'103': 'G'
	};
	if(typeof(codes[char_code]) === 'string') {
		return codes[char_code];
	} else {
		return false;
	}
}

function sbk_charCode_to_bass_note(char_code) {
	var codes = {
		'65': 'a', //A
		'66': 'b', //B
		'67': 'c', //C
		'68': 'd', //D
		'69': 'e',
		'70': 'f',
		'71': 'g',
		
		'97': 'a', //a
		'98': 'b', //b
		'99': 'c', //c
		'100': 'd', //d
		'101': 'e',
		'102': 'f',
		'103': 'g'
	};
	if(typeof(codes[char_code]) === 'string') {
		return codes[char_code];
	} else {
		return false;
	}
}

function sbk_charCode_to_chord_modifier(char_code) {
	//console.log(char_code);
	var codes = {
		'68': 'dim',
		'100': 'dim',
		'45': 'dim', //-
		'109': 'm', 
		'77': 'm',
		'55': '7',
		'43': 'aug', //+
		'97': 'aug', //a
		'65': 'aug', //A
	};
	if(typeof(codes[char_code]) === 'string') {
		return codes[char_code];
	} else {
		return false;
	}
}

function sbk_add_chord() {
	var textarea, caret_position, chord_text = '', bass_note = false, bass_note_modifier = false;
	
	textarea = jQuery('textarea#content');

	textarea.unbind('keypress').bind('keypress', function (event) {
		var key = false;

		//allow backspace to work
		if(event.charCode === 0) { //backspace
			chord_text = chord_text.slice(0, -1);
			if (bass_note !== false) {
				bass_note = bass_note - 1; 
			}
			if(bass_note === 0) {
				bass_note = false;
			}
			return true;
		} else {
			if (bass_note === false) { //chord mode
				if (chord_text.length === 0) { //the first character must be a key (capital)
					key = sbk_charCode_to_key_text(event.charCode);
				} else { //other notes
					if(chord_text.length === 1) { // flat or sharp characters only allowed in second character
						if(event.charCode == '66' | event.charCode == '98') { // B or b
							chord_text = chord_text + 'b';
							textarea.insertAtCaretPos('b');
						}
						if(event.charCode == '35') { // #
							chord_text = chord_text + '#';
							textarea.insertAtCaretPos('#');
						}
					}
					if(event.charCode == '47') { // "/" - bass note coming - change to bass note mode
						key = '/';
						bass_note = 1;
					} else {
						key = sbk_charCode_to_chord_modifier(event.charCode);
					}
				}
			} else { // bass note mode
				if (bass_note === 1) { // first bass note character must be a key (lower case)
					key = sbk_charCode_to_bass_note(event.charCode);
					bass_note = bass_note + 1;
				} else if (bass_note === 2) { //bass notes can only have two characters - a key and a flat or sharp
					if(event.charCode == '66' | event.charCode == '98') { // B or b
						chord_text = chord_text + 'b';
						textarea.insertAtCaretPos('b');
					}
					if(event.charCode == '35') { // #
						chord_text = chord_text + '#';
						textarea.insertAtCaretPos('#');
					}
					bass_note = bass_note + 1;
				} 
			}
			if (key) {
				chord_text = chord_text + key;
				textarea.insertAtCaretPos(key);
			}
			return false;
		}
	});
	textarea.insertAtCaretPos('[');
	textarea.insertAtCaretPos(']');
	caret_position = textarea.getSelection().start;
	//move the caret back one, so that the insert point is between the brackets
	textarea.setCaretPos(caret_position);

	
}

function sbk_enter_add_chords_mode() {
	var textarea, anchor, caret_position, chord_text = '', key = false;
	
	textarea = jQuery('textarea#content');
	anchor = jQuery('a#add_chords');
	
	textarea.bind('click', sbk_add_chord);
	
	anchor.unbind('click').html('exit chord mode').click(sbk_exit_add_chords_mode).addClass('chord_mode');
}

function sbk_exit_add_chords_mode(anchor){
	var textarea, anchor;
	
	textarea = jQuery('textarea#content');
	anchor = jQuery('a#add_chords');
	
	textarea.unbind('keypress').unbind('click');
	anchor.unbind('click').html('add chords').click(sbk_enter_add_chords_mode).removeClass('chord_mode');
}
