$(document).ready(function() {

	jQuery('#playlist-holder').each(function () {
		var self = jQuery(this), playlist;

		playlist = new SBK.PlayList(self.attr('filename'), self);
		playlist.render();
	});

	jQuery('.side_2').each(function () {
		var self = jQuery(this);

		song_picker = new SBK.SongPicker(self);
		song_picker.render();
	});


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
