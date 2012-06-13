$(document).ready(function() {

});

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
