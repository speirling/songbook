/* jQuery plugins */

$.fn.insertAtCaret = function(text, set_caret_position_before_or_after) { // I was unable to position the caret inside the inserted text, so I decided to positionion it after one half of the text and before the second half
    return this.each(function() {
        var offset;
        if (typeof(set_caret_position_before_or_after) !== 'undefined' && set_caret_position_before_or_after === 'before') {
            offset = 0;
        } else {
            offset = text.length;
        }
        if (document.selection && this.tagName == 'TEXTAREA') {  //IE textarea
            this.focus();
            sel = document.selection.createRange();
            sel.text = text;
            this.focus();
        } else if (this.selectionStart || this.selectionStart == '0') { //MOZILLA textarea
            startPos = this.selectionStart;
            endPos = this.selectionEnd;
            scrollTop = this.scrollTop;
            this.value = this.value.substring(0, startPos) + text + this.value.substring(endPos, this.value.length);
            this.focus();
            this.selectionStart = startPos + offset;
            this.selectionEnd = startPos + offset;
            this.scrollTop = scrollTop;
        } else {
            //non textarea
            var selectionObject = window.getSelection();
            var range = selectionObject.getRangeAt(0);
            text_node = document.createTextNode(text);
            range.insertNode(text_node);
            range.setStart(text_node, offset);
            range.setEnd(text_node, offset);
        }
    });
};


/* SBK static functions */
SBK.StaticFunctions = {

    charCode_to_key_text: function (char_code) {
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
    },
    
    charCode_to_bass_note: function (char_code) {
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
    },
    
    charCode_to_chord_modifier: function (char_code) {
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
            '54': '6', //6
            '57': '9', //9
            '52': 'sus4' //4
        };
        if(typeof(codes[char_code]) === 'string') {
            return codes[char_code];
        } else {
            return false;
        }
    },
    
    charCode_to_key_modifier: function (char_code) {
        var codes = {
            '66': 'b', //B
            '98': 'b', //b
            '35': '#'
        };
        if(typeof(codes[char_code]) === 'string') {
            return codes[char_code];
        } else {
            return false;
        }
    },
    
    find_note_number: function (original_value, adjustment) {
        var new_value;

        new_value = original_value + adjustment;
        if (new_value < 0) {
            new_value = 12 + new_value;
        }
        if (new_value > 11) {
            new_value = new_value - 12;
        }

        return new_value;
    },

    note_to_upper: function(note) {
        var base_note, upper_note;

        base_note = note.substring(0, 1);
        upper_note = base_note.toUpperCase();
        
        if(note.length == 2) {
            upper_note = upper_note + note.substr(note, 1, 1);
        }
        return upper_note;
    },

    note_to_lower: function(note) {
        var base_note, lower_note;

        base_note = note.substring(0, 1);
        lower_note = base_note.toLowerCase();

        if (note.length == 2) {
            lower_note = lower_note + note.substr(1, 1);
        }

        return lower_note;
    },

    shift_note: function(note, adjustment, use_sharps) {
        var lowercase, new_note_number, new_note;

        lowercase = false;
        
        if (SBK.StaticFunctions.note_to_lower(note) === note) {
            lowercase = true;
        }
        new_note_number = SBK.StaticFunctions.find_note_number(SBK.Constants.NOTE_VALUE_ARRAY[SBK.StaticFunctions.note_to_upper(note)], adjustment);
        if (typeof(use_sharps) === 'undefined') {
            new_note = SBK.Constants.VALUE_NOTE_ARRAY_DEFAULT[new_note_number];
        } else if(use_sharps === true) {
            new_note = SBK.Constants.VALUE_NOTE_ARRAY_SHARP[new_note_number];
        } else {
            new_note = SBK.Constants.VALUE_NOTE_ARRAY_FLAT[new_note_number];
        }
        if(lowercase) {
            new_note = SBK.StaticFunctions.note_to_lower(new_note);
        }

        return new_note;
    },
    
    transpose_chord: function (chord, base_key, target_key) {
        var chord_note, second_char, modifier_start, chord_modifier, key_conversion_value, new_chord, bass_key, slash_position, old_bass_key, new_bass_key;

        if(base_key === null || base_key === '') {
            throw new Exception("SBK.StaticFunctions.transpose_chord() :: no base key passed");
        }
        if(target_key === null || target_key === '') {
            throw new Exception("SBK.StaticFunctions.transpose_chord() :: no target key passed");
        }
        chord_note = chord.substring(0, 1);
        second_char = chord.substring(1, 1);
        modifier_start = 1;
        if(second_char === '#' || second_char == 'b') {
            chord_note = chord_note + second_char;
            modifier_start = 2;
        }
        chord_modifier = chord.substring(modifier_start);

        key_conversion_value = SBK.Constants.NOTE_VALUE_ARRAY[target_key] - SBK.Constants.NOTE_VALUE_ARRAY[base_key];
        new_chord = SBK.StaticFunctions.shift_note(chord_note, key_conversion_value);

        bass_key = '';
        slash_position = chord_modifier.indexOf('/');
        if (slash_position > -1) {
            new_chord_modifier = chord_modifier.substring(0, slash_position);
            old_bass_key = chord_modifier.substring(slash_position + 1);
            new_bass_key = SBK.StaticFunctions.shift_note(old_bass_key, key_conversion_value);
            bass_key = '/' + new_bass_key;
        } else {
            new_chord_modifier = chord_modifier;
        }

        new_chord = new_chord + new_chord_modifier + bass_key;

        return new_chord;
    },
    
    parse_chord_string: function (chord_string) {
        var second_char, modifier_start, slash_position;
        
        result = {};
        if(chord_string !== '') {
            result.note = chord_string.substring(0, 1);
            second_char = chord_string.substring(1, 1);
            modifier_start = 1;
            if (second_char === '#' || second_char == 'b') {
                result.note = result.note + second_char;
                modifier_start = 2;
            }
            result.modifier = chord_string.substring(modifier_start);
    
            slash_position = result.modifier.indexOf('/');
            if (slash_position > -1) {
                result.bass_note = result.modifier.substring(slash_position + 1);
                result.modifier = result.modifier.substring(0, slash_position);
            }
        }

        return result;
    },
    
    undefined_to_null: function (value) {
        if (typeof(value) === 'undefined') {
            return null;
        } else {
            return value;
        }
    },
    
    value_or_blank: function (value) {
        var self = this, result;
        
        if (typeof(value) === 'string') {
            result = value;
        } else {
            result = '';
        }
        
        return result;
    },
    
    chord_entry_keypress: function (keypress_event, textarea, chord_text) {
        var key = false, cc, bass_note = false, bass_note_modifier = false;
        
        cc = keypress_event.charCode;
        console.log('chord_entry_keypress ', cc, ' - ', keypress_event, chord_text); 
        //allow backspace to work
        if (cc === 0) { //backspace
            chord_text = chord_text.slice(0, -1);
            if (bass_note !== false) {
                bass_note = bass_note - 1; 
            }
            if (bass_note === 0) {
                bass_note = false;
            }
            return true;
        } else {
            if (bass_note === false) { //chord mode
                if (chord_text.length === 0) { //the first character must be a key (capital)
                    key = SBK.StaticFunctions.charCode_to_key_text(cc);
                } else { //other notes
                    if(chord_text.length === 1) { // flat or sharp characters only allowed in second character
                        if(cc == '66' | cc == '98') { // B or b
                            chord_text = chord_text + 'b';
                            textarea.insertAtCaret('b');
                        }
                        if(cc == '35') { // #
                            chord_text = chord_text + '#';
                            textarea.insertAtCaret('#');
                        }
                    }
                    if(cc == '47') { // "/" - bass note coming - change to bass note mode
                        key = '/';
                        bass_note = 1;
                    } else {
                        key = SBK.StaticFunctions.charCode_to_chord_modifier(cc);
                    }
                }
            } else { // bass note mode
                if (bass_note === 1) { // first bass note character must be a key (lower case)
                    key = SBK.StaticFunctions.charCode_to_bass_note(cc);
                    bass_note = bass_note + 1;
                } else if (bass_note === 2) { //bass notes can only have two characters - a key and a flat or sharp
                    if(cc == '66' | cc == '98') { // B or b
                        chord_text = chord_text + 'b';
                        textarea.insertAtCaret('b');
                    }
                    if(cc == '35') { // #
                        chord_text = chord_text + '#';
                        textarea.insertAtCaret('#');
                    }
                    bass_note = bass_note + 1;
                } 
            }
            if (key) {
                chord_text = chord_text + key;
                textarea.insertAtCaret(key);
            }
            console.log(chord_text);
            return false;
        }
    }
};