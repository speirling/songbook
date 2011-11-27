$(document).ready(function() {
	
	display_playlist_editor ();
	create_filter_list(jQuery('#available-songs'));

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
        		        click: function(element){ window.open('?action=displaySong&id=' + element.attr('id').replace('id_', '')); }
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
	  "text": jQuery('.introduction.songlist .introduction_text', source).val(),
	  "duration": jQuery('.introduction.songlist .introduction_duration', source).val()
	};
	output_json.sets = [];
	set_count = 0;
	jQuery('li.set', source).each(function () {
		var this_set = jQuery(this), song_count;

		output_json.sets[set_count] = {
			"label": jQuery('.set-title', this_set).val(),
			"introduction": {
				"text": jQuery('.introduction.set .introduction_text', this_set).val(),
				"duration": jQuery('.introduction.set .introduction_duration', this_set).val()
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
				"duration": jQuery('.duration', self).val(),
				"introduction": {
					"text": jQuery('.introduction_text', self).val(),
					"duration": jQuery('.introduction_duration', self).val()
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
	        		jQuery('ul', container).sortable({
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

function sbk_enforce_chord_text(text) {
	console.log(text, text.val());
}

function sbk_charCode_to_chord_text(char_code) {
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

function sbk_charCode_to_chord_modifier(char_code) {
	//console.log(char_code);
	var codes = {
		'68': 'dim',
		'100': 'dim',
		'109': 'm', 
		'77': 'm',
		'55': '7',
		'43': 'aug',
		'97': 'aug',
		'65': 'aug'
	};
	if(typeof(codes[char_code]) === 'string') {
		return codes[char_code];
	} else {
		return false;
	}
}

function sbk_add_chord() {
	var textarea, caret_position, chord_text = '', key = false;
	
	textarea = jQuery('textarea#content');

	textarea.unbind('keypress').bind('keypress', function (event) {
		if(chord_text.length === 0) {
			key = sbk_charCode_to_chord_text(event.charCode);
		} else {
			//allow backspace to work
			if(event.charCode === 0) {
				chord_text = chord_text.slice(0, -1);
				return true;
			}
			if(chord_text.length === 1) {
				if(event.charCode == '66' | event.charCode == '98') {
					chord_text = chord_text + 'b';
					textarea.insertAtCaretPos('b');
				}
			}
			key = sbk_charCode_to_chord_modifier(event.charCode);
		}
		if(key) {
			chord_text = chord_text + key;
			textarea.insertAtCaretPos(key);
		}
		return false;
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