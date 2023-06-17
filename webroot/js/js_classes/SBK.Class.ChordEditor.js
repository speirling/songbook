SBK.ChordEditor = SBK.Class.extend({
	init: function (lyrics_container) {
		var self = this;

        self.target = lyrics_container;
        self.container = jQuery('<div class="chord-editor"></div>').prependTo(self.target.parent()).hide();
        self.on_off_switch = new SBK.Button(self.target.parent(), 'chord-mode', 'Chord mode', function () {self.enter_add_chords_mode();});
        //self.on_off_switch.position({my: "right top", at: "right top", of: self.target}); //this depends on jQueryUI, with seems abandoned so I've removed it 20230616 
        self.chord_object = {};
        self.bass_note_requested = false;
        self.initial_value = '';
        self.display_mode = 'static';
        self.range = null;
	},

	chord_editor_callback: function (chord_string, range) {
        if (chord_string !== '') {
            chord_string = '[' + chord_string + ']';
        }
        if (typeof(range.container) === 'undefined') {
            range.deleteContents();
            range.insertNode(document.createTextNode(chord_string));
        } else {
            SBK.StaticFunctions.replace_textbox_selection(range, chord_string);  //for textarea
        }
    },

    enter_add_chords_mode: function () {
        var self = this, caret_position, chord_text = '', key = false, selectionObject, current_value;

        if (self.display_mode === 'static') {
            self.container.show();
        }
        self.target.bind('click', function (event) {
            self.open(self.target, event.pageX, event.pageY);
        });
        self.on_off_switch.set_text('exit chord mode');
        //self.on_off_switch.position({my: "right top", at: "right top", of: self.target});
        self.on_off_switch.click(function () {self.exit_add_chords_mode();});
        self.on_off_switch.addClass('chord-mode-active');
    },

    exit_add_chords_mode: function () {
        var self = this;

        if(self.display_mode === 'static') {
            self.container.hide();
        }
        self.target.unbind('keypress').unbind('click');
        self.on_off_switch.set_text('Chord mode');
        //self.on_off_switch.position({my: "right top", at: "right top", of: self.target});
        self.on_off_switch.click(function () {self.enter_add_chords_mode();});
        self.on_off_switch.removeClass('chord-mode-active');
    },
	
	key_buttons: function (modifier, container, classname, callback) {
        var self = this;

        jQuery('<div class="button button-note ' + classname + ' button-note-white button-note-c"><span><span class="note-name">C</span>' + modifier +'</span></div>').appendTo(container).click(function () { 
            callback('C'); 
        });
        jQuery('<div class="button button-note ' + classname + ' button-note-black button-note-csharp"><span><span class="note-name">C#</span>' + modifier +'</span></div>').appendTo(container).click(function () { 
            callback('C#');  
        });
        jQuery('<div class="button button-note ' + classname + ' button-note-black button-note-csharp"><span><span class="note-name">Db</span>' + modifier +'</span></div>').appendTo(container).click(function () { 
            callback('Db');  
        });
        jQuery('<div class="button button-note ' + classname + ' button-note-white button-note-d"><span><span class="note-name">D</span>' + modifier +'</span></div>').appendTo(container).click(function () { 
            callback('D'); 
        });
        jQuery('<div class="button button-note ' + classname + ' button-note-black button-note-dsharp"><span><span class="note-name">D#</span>' + modifier +'</span></div>').appendTo(container).click(function () { 
            callback('D#'); 
        });
        jQuery('<div class="button button-note ' + classname + ' button-note-black button-note-eflat"><span><span class="note-name">Eb</span>' + modifier +'</span></div>').appendTo(container).click(function () { 
            callback('Eb'); 
        });
        jQuery('<div class="button button-note ' + classname + ' button-note-white button-note-e"><span><span class="note-name">E</span>' + modifier +'</span></div>').appendTo(container).click(function () { 
            callback('E');  
        });
        jQuery('<div class="button button-note ' + classname + ' button-note-white button-note-f"><span><span class="note-name">F</span>' + modifier +'</span></div>').appendTo(container).click(function () { 
            callback('F'); 
        });
        jQuery('<div class="button button-note ' + classname + ' button-note-black button-note-fsharp"><span><span class="note-name">F#</span>' + modifier +'</span></div>').appendTo(container).click(function () { 
            callback('F#'); 
        });
        jQuery('<div class="button button-note ' + classname + ' button-note-black button-note-gflat"><span><span class="note-name">Gb</span>' + modifier +'</span></div>').appendTo(container).click(function () { 
            callback('Gb'); 
        });
        jQuery('<div class="button button-note ' + classname + ' button-note-white button-note-g"><span><span class="note-name">G</span>' + modifier +'</span></div>').appendTo(container).click(function () { 
            callback('G'); 
        });
        jQuery('<div class="button button-note ' + classname + ' button-note-black button-note-gsharp"><span><span class="note-name">G#</span>' + modifier +'</span></div>').appendTo(container).click(function () { 
            callback('G#'); 
        });
        jQuery('<div class="button button-note ' + classname + ' button-note-black button-note-aflat"><span><span class="note-name">Ab</span>' + modifier +'</span></div>').appendTo(container).click(function () { 
            callback('Ab'); 
        });
        jQuery('<div class="button button-note ' + classname + ' button-note-white button-note-a"><span><span class="note-name">A</span>' + modifier +'</span></div>').appendTo(container).click(function () { 
            callback('A'); 
        });
        jQuery('<div class="button button-note ' + classname + ' button-note-black button-note-asharp"><span><span class="note-name">A#</span>' + modifier +'</span></div>').appendTo(container).click(function () { 
            callback('A#'); 
        });
        jQuery('<div class="button button-note ' + classname + ' button-note-black button-note-aflat"><span><span class="note-name">Bb</span>' + modifier +'</span></div>').appendTo(container).click(function () { 
            callback('Bb'); 
        });
        jQuery('<div class="button button-note ' + classname + ' button-note-white button-note-b"><span><span class="note-name">B</span>' + modifier +'</span></div>').appendTo(container).click(function () { 
            callback('B'); 
        });

	},

    render: function () {
        var self = this;

        self.buttons = {};

        self.input = jQuery('<div contenteditable="true" class="chord-editor-input"></div>').appendTo(self.container);
        self.buttons.backspace = jQuery('<div class="button button-submit button-submit-backspace"><span>Back<span></div>').appendTo(self.container).click(function () { self.register_backspace(self.get_value()); });
        self.buttons.close = jQuery('<div class="button button-submit button-submit-close"><span>Set<span></div>').appendTo(self.container).click(function () { self.close(); });

        self.button_containermodifiers = jQuery('<div class="container container-modifiers"></div>').appendTo(self.container);
        self.button_containernotes = jQuery('<div class="container container-notes"></div>').appendTo(self.container);
        /*self.buttons.key_note = jQuery('<div class="button button-note-type button-note-type-key selected"><span>key</span></div>').appendTo(self.button_containernotes).click(function () { 
            self.bass_note_requested = false;
            self.buttons.key_note.addClass('selected');
            self.buttons.bass_note.removeClass('selected');
        });
        self.buttons.bass_note = jQuery('<div class="button button-note-type button-note-type-bass"><span>bass</span></div>').appendTo(self.button_containernotes).click(function () { 
            self.bass_note_requested = true;
            self.buttons.key_note.removeClass('selected');
            self.buttons.bass_note.addClass('selected');
        });*/

        self.button_containernotes_minor7 = jQuery('<div class="container container-notes-minor7"></div>').appendTo(self.container);
        self.button_containernotes_minor = jQuery('<div class="container container-notes-minor"></div>').appendTo(self.container);
        self.button_containernotes_dom7 = jQuery('<div class="container container-notes-dom7"></div>').appendTo(self.container);
        self.button_containernotes_major = jQuery('<div class="container container-notes-major"></div>').appendTo(self.container);
        self.button_containernotes_bass = jQuery('<div class="container container-notes-bass"></div>').appendTo(self.container);


        self.key_buttons('', self.button_containernotes_major, 'button-note-major', function (note_letter) { 
            self.chord_object.note = note_letter.substr(0,1).toUpperCase() + note_letter.substr(1).toLowerCase();
            self.chord_object.modifier = ''; 
            self.display_value();
        });

        self.key_buttons('m', self.button_containernotes_minor, 'button-note-minor', function (note_letter) { 
            self.chord_object.note = note_letter.substr(0,1).toUpperCase() + note_letter.substr(1).toLowerCase();
            self.chord_object.modifier = 'm'; 
            self.display_value();
        });

        self.key_buttons('7', self.button_containernotes_dom7, 'button-note-dom7', function (note_letter) { 
            self.chord_object.note = note_letter.substr(0,1).toUpperCase() + note_letter.substr(1).toLowerCase(); 
            self.chord_object.modifier = '7'; 
            self.display_value();
        });

        self.key_buttons('m7', self.button_containernotes_minor7, 'button-note-minor7', function (note_letter) { 
            self.chord_object.note = note_letter.substr(0,1).toUpperCase() + note_letter.substr(1).toLowerCase();
            self.chord_object.modifier = 'm7'; 
            self.display_value();
        });

        self.key_buttons('', self.button_containernotes_bass, 'button-note-bass', function (note_letter) { 
            self.chord_object.bass_note = note_letter.toLowerCase();
            self.display_value();
        });

        self.buttons.modifier_major = jQuery('<div class="button button-modifier button-modifier-major"><span>maj</span></div>').appendTo(self.button_containermodifiers).click(function () { 
            self.chord_object.modifier = ''; 
            self.display_value();
        });
        self.buttons.modifier_minor = jQuery('<div class="button button-modifier button-modifier-minor"><span>min</span></div>').appendTo(self.button_containermodifiers).click(function () { 
            self.chord_object.modifier = 'm';  
            self.display_value();
        });
        self.buttons.modifier_augmented = jQuery('<div class="button button-modifier button-modifier-minor"><span>&nbsp;sus4&nbsp;</span></div>').appendTo(self.button_containermodifiers).click(function () { 
            self.chord_object.modifier = 'sus4';  
            self.display_value();
        });
        self.buttons.modifier_augmented = jQuery('<div class="button button-modifier button-modifier-minor"><span>&nbsp;6&nbsp;</span></div>').appendTo(self.button_containermodifiers).click(function () { 
            self.chord_object.modifier = '6';  
            self.display_value();
        });
        self.buttons.modifier_dominantseventh = jQuery('<div class="button button-modifier button-modifier-seventh"><span>dom7</span></div>').appendTo(self.button_containermodifiers).click(function () { 
            self.chord_object.modifier = '7';  
            self.display_value();
        });
        self.buttons.modifier_minorseventh = jQuery('<div class="button button-modifier button-modifier-minor"><span>min7</span></div>').appendTo(self.button_containermodifiers).click(function () { 
            self.chord_object.modifier = 'm7';  
            self.display_value();
        });
        self.buttons.modifier_augmented = jQuery('<div class="button button-modifier button-modifier-minor"><span>maj7</span></div>').appendTo(self.button_containermodifiers).click(function () { 
            self.chord_object.modifier = 'maj7';  
            self.display_value();
        });
        self.buttons.modifier_augmented = jQuery('<div class="button button-modifier button-modifier-minor"><span>&nbsp;9&nbsp;</span></div>').appendTo(self.button_containermodifiers).click(function () { 
            self.chord_object.modifier = '9';  
            self.display_value();
        });
        self.buttons.modifier_diminished = jQuery('<div class="button button-modifier button-modifier-minor"><span>-dim</span></div>').appendTo(self.button_containermodifiers).click(function () { 
            self.chord_object.modifier = '-';  
            self.display_value();
        });
        self.buttons.modifier_augmented = jQuery('<div class="button button-modifier button-modifier-minor"><span>+aug</span></div>').appendTo(self.button_containermodifiers).click(function () { 
            self.chord_object.modifier = '+';  
            self.display_value();
        });

        

        self.input.bind('keypress', function (event) {
            self.handle_charCode(event.charCode);
            return false;
        });
    },

    open: function (container, x, y) {
        var self = this;

        if (container[0].tagName.toLowerCase() === 'textarea') {
            self.open_textarea(container, x, y);
        } else {
            self.open_editable_div (window.getSelection(), x, y);
        }
    },
    
    open_editable_div: function (selectionObject, x, y) {
        var self = this, chord_string = '', count_backwards = 0, count_forwards = 0, rewind_index = 0;

        //if the chord editor is already active, set its previous range to its previous value.
        if (self.range !== null) {
            self.chord_editor_callback(self.get_value(), self.range);
        }
        if (typeof(selectionObject) === 'undefined') {
            chord_string = ''; 
            self.range = null;
        } else {
            
            self.selection = selectionObject;
            self.range = self.selection.getRangeAt(0);
            //check if the user has clicked or selected within a chord
            for (count_backwards = 0; count_backwards < 10; count_backwards = count_backwards + 1) {
                self.selection.modify("extend", "backward", "character");
                chord_string = self.selection.toString().trim();
                if (chord_string.substr(0, 1) === '[') {
                    break;
                } else if (chord_string.substr(0, 1) === ']') {
                    //you're incroaching on a previous chord - don't do it!
                    self.selection.modify("extend", "forward", "character");
                    break;
                } 
            }
            self.selection.collapseToStart();
            self.selection.modify("extend", "forward", "character");
            chord_string = self.selection.toString().trim();
            if (chord_string.substr(0, 1) === '[') {
                for (count_forwards = 0; count_forwards < 20; count_forwards = count_forwards + 1) {
                    self.selection.modify("extend", "forward", "character");
                    chord_string = self.selection.toString().trim();
                    if (chord_string.substr(chord_string.length -1) === ']') {
                        break;
                    } else if (chord_string.substr(chord_string.length -1) === '[') {
                        // This shouldn't happen - it means you have a chord inside square brackets. Should you select the outer brackets, or assume that you should insert at a point?
                        // insert at the clicked point.
                        break;
                    } 
                }
            }
            chord_string = self.selection.toString().trim();
            last_char = chord_string.substr(chord_string.length -1);
            first_char = chord_string.substr(0, 1);
                
            if (first_char === '[' && last_char === ']') {
                chord_string = chord_string.substr(1, chord_string.length - 2);
                self.range = window.getSelection().getRangeAt(0);
            } else {
                //insert at a single point, so move the selection back to where you started
                distance_back = count_backwards - count_forwards;
                if (distance_back < 0) {
                    distance_forward = distance_back * -1;
                    for(rewind_index = 0; rewind_index < distance_forward - 1; rewind_index = rewind_index + 1) {
                        self.selection.modify("extend", "backward", "character");
                    }
                    self.selection.collapseToStart();
                } else {
                    for(rewind_index = 0; rewind_index < distance_back - 1; rewind_index = rewind_index + 1) {
                        self.selection.modify("extend", "forward", "character");
                    }
                    self.selection.collapseToEnd();
                }
                chord_string = '';
            }



            self.range.deleteContents();
        }
        self.bass_note_requested = false;
        /*self.buttons.key_note.addClass('selected');
        self.buttons.bass_note.removeClass('selected');*/

        self.initial_value = chord_string;
        self.chord_object = SBK.StaticFunctions.parse_chord_string(chord_string);
        if(self.display_mode === 'floating') {
            if (typeof(x) !== 'undefined' && typeof(y) !== 'undefined') {
                if (x !== null && y !== null) {
                    self.container.css({"top": y, "left": x, "position": "absolute"}).show();
                }
            }
        }
        self.display_value();
        self.bass_note_requested = false;
        self.input.focus();

    },

    open_textarea: function (tbx, x, y) {
        var self = this, chord_string = '', count_backwards = 0, count_forwards = 0, rewind_index = 0, old_content, new_content, original_start, original_end;

        //if the chord editor is called on a textbox, selectionObject doesn't work, range has to be defined in characters within the textbox
        self.range = {container: tbx[0], start: tbx[0].selectionStart, end: tbx[0].selectionEnd};

        original_start = self.range.start;
        original_end = self.range.end;
        //check if the user has clicked or selected within a chord
        for (count_backwards = 0; count_backwards < 10; count_backwards = count_backwards + 1) {
            self.range.container.selectionStart = self.range.container.selectionStart - 1;
            chord_string = self.range.container.value.substring(self.range.container.selectionStart, self.range.container.selectionEnd).trim();
            if (chord_string.substr(0, 1) === '[') {
                break;
            } else if (chord_string.substr(0, 1) === ']') {
                //you're incroaching on a previous chord - don't do it!
                self.range.container.selectionStart = self.range.container.selectionStart + 1;
                break;
            } 
        }
        if (chord_string.substr(0, 1) === '[') { //if you've found the start of a chord before the insertion click without finding a chord end, then you're probably inside a chord
            for (count_forwards = 0; count_forwards < 20; count_forwards = count_forwards + 1) {
                self.range.container.selectionEnd = self.range.container.selectionEnd + 1;
                chord_string = self.range.container.value.substring(self.range.container.selectionStart, self.range.container.selectionEnd).trim();
                if (chord_string.substr(chord_string.length -1) === ']') {
                    break;
                } else if (chord_string.substr(chord_string.length -1) === '[') {
                    // This shouldn't happen - it means you have a chord inside square brackets. Should you select the outer brackets, or assume that you should insert at a point?
                    // insert at the clicked point.
                    break;
                } 
            }
        }
        //if you've clicked within a chord the the current chord string will start with [ and end with ]
        chord_string = self.range.container.value.substring(self.range.container.selectionStart, self.range.container.selectionEnd).trim();
        last_char = chord_string.substr(chord_string.length -1);
        first_char = chord_string.substr(0, 1);
            
        if (first_char === '[' && last_char === ']') {
            chord_string = chord_string.substr(1, chord_string.length - 2);
            self.range.start = self.range.container.selectionStart;
            self.range.end = self.range.container.selectionEnd;
            self.range = SBK.StaticFunctions.replace_textbox_selection(self.range, chord_string);
            
        } else {
            //insert at a single point, so move the selection back to where you started
            self.range.container.selectionStart = original_start;
            self.range.container.selectionEnd = original_start;
            chord_string = '';
        } 

        self.bass_note_requested = false;

        self.initial_value = chord_string;
        self.chord_object = SBK.StaticFunctions.parse_chord_string(chord_string);
        if(self.display_mode === 'floating') {
            if (typeof(x) !== 'undefined' && typeof(y) !== 'undefined') {
                if (x !== null && y !== null) {
                    self.container.css({"top": y, "left": x, "position": "absolute"}).show();
                }
            }
        }
        self.display_value();
        self.bass_note_requested = false;
        self.input.focus();
    },

    close: function () {
       var self = this;

       if (self.display_mode === 'floating') {
           self.container.hide();
       }
       //if (self.get_value() !== self.initial_value) {
           self.chord_editor_callback(self.get_value(), self.range);
       //}
    },

    display_value: function () {
        var self = this, chord_html = '', v;

        if (typeof(self.chord_object.note) !== 'undefined') {
            chord_html = chord_html + '<span class="note">' + self.chord_object.note + '</span>';
        }
        if (typeof(self.chord_object.modifier) !== 'undefined') {
            chord_html = chord_html + '<span class="modifier">' + self.chord_object.modifier + '</span>';
        }
        if (typeof(self.chord_object.bass_note) !== 'undefined') {
            chord_html = chord_html + '<span class="bass_note_divider">/</span>';
            chord_html = chord_html + '<span class="bass_note">' + self.chord_object.bass_note + '</span>';
        }
        self.input.html(chord_html);
        v = self.get_value();
        if (v === '') {
            v = '-';
        }
        self.chord_editor_callback(v, self.range);
    },

    get_value: function () {
        var self = this;

        return self.input.text();
    },

    handle_charCode: function (cc) {
        var self = this, text;

        //allow backspace to work
        if (cc === 0) { //backspace
            self.register_backspace(self.get_value());
        } else {
            
            if (cc == '47') {
                self.bass_note_requested = true;
            } else {
                switch (self.next_part(cc)) {
                case 'note':
                        self.bass_note_requested = false;
                        text = SBK.StaticFunctions.charCode_to_key_text(cc);
                        if (text) {
                            self.chord_object.note = text;
                        }
                    break;
                    
                case 'note_modifier':
                        self.bass_note_requested = false;
                        text = self.chord_object.note + SBK.StaticFunctions.charCode_to_key_modifier(cc);
                        if (text) {
                            self.chord_object.note = text;
                        }
                    break;
                        
                case 'chord_modifier':
                    self.bass_note_requested = false;
                    text = SBK.StaticFunctions.charCode_to_chord_modifier(cc);
                    if (text) {
                        self.chord_object.modifier = text;
                    }
                    break;
                    
                case 'bass_note':
                    self.bass_note_requested = false;
                    text = SBK.StaticFunctions.charCode_to_bass_note(cc); 
                    if(text) {
                        self.chord_object.bass_note = text;
                    }
                    break;

                case 'bass_note_modifier':
                    self.bass_note_requested = false;
                    text = self.chord_object.bass_note + SBK.StaticFunctions.charCode_to_key_modifier(cc);
                    if (text) {
                        self.chord_object.bass_note = text;
                    }
                    break;
                }
            }
        }
        self.display_value();
        
        return false;
    },

    handle_note_button: function (note) {
        var self = this;

        if (self.bass_note_requested === false) {
            self.chord_object.note = note.toUpperCase();
        } else {
            self.chord_object.bass_note = note.toLowerCase();
        }
        self.display_value();
        
        return false;
    },

    register_backspace: function (chord_text) {
        var self = this, bass_note, note, key = false, bass_note = false, bass_note_modifier = false, chord_text, char;

        if (typeof(self.chord_object.bass_note) !== 'undefined') {
            bass_note = self.chord_object.bass_note.slice(0, -1);
            if (bass_note.length === 0) {
                delete self.chord_object.bass_note;
            } else {
                self.chord_object.bass_note = bass_note;
            }
        } else if (typeof(self.chord_object.modifier) !== 'undefined') {
            delete self.chord_object.bass_note;
            delete self.chord_object.modifier;
        } else if (typeof(self.chord_object.note) !== 'undefined') {
            delete self.chord_object.bass_note;
            delete self.chord_object.modifier;
            note = self.chord_object.note.slice(0, -1);
            if (note.length === 0) {
                delete self.chord_object.note;
            } else {
                self.chord_object.note = note;
            }
        }
        self.display_value();
    },

    next_part: function (cc) { // determine how keypress should be determined - what part of the chord are you in - note, modifier, bassnote or bassnote modifier
        var self = this, result = false, bass_note, note, key = false, bass_note = false, bass_note_modifier = false, chord_text, char;

            if (typeof(self.chord_object.bass_note) === 'undefined') {
                if (typeof(self.chord_object.modifier) === 'undefined') {
                    //chord mode
                    if (typeof(self.chord_object.note) ==='undefined') {
                        chord_text = '';
                    } else {
                       chord_text = self.chord_object.note;
                    }
                    if (chord_text.length === 0) { //the first character must be a key (capital)
                        //no chord yet - first thing to enter MUST be a key letter
                        result = 'note';
                    } else { //other notes - could be a sharp or flat (key modifier) dim aug etc. (chord modifier)
                        if (chord_text.length === 1) { 
                           //in chord mode, and there's still room for a sharp or flat
                            if (self.bass_note_requested) { //self.bass_note_requested means '/' has been pressed - it's only accepted if a valid chord has been registered
                                result = 'bass_note';
                            } else if (cc == '66' | cc == '98' | cc == '35'){
                                result = 'note_modifier';
                            } else {
                                //modifier mode
                                if (self.bass_note_requested) { // "/" - bass note coming - change to bass note mode
                                    result = 'bass_note';
                                } else {
                                    result = 'chord_modifier';
                                }
                            }
                        } else {
                            //modifier mode
                            if (self.bass_note_requested) { // "/" - bass note coming - change to bass note mode
                                result = 'bass_note';
                            } else {
                                result = 'chord_modifier';
                            }
                        }
                    }
                } else {
                    //modifier mode
                    if (self.bass_note_requested) { // "/" - bass note coming - change to bass note mode
                        result = 'bass_note';
                    } else {
                        result = 'chord_modifier';
                    }
                }
            } else {
             // bass note mode
                if (typeof(self.chord_object.bass_note) ==='undefined') {
                    return false;
                } else {
                    bass_note_length = self.chord_object.bass_note.length;

                    if (bass_note_length === 0) { // first bass note character must be a key (lower case)
                        return 'bass_note';
                    } else if (bass_note_length === 1) { //bass notes can only have two characters - a key and a flat or sharp
                        return 'bass_note_modifier';
                    } else {
                        return false;
                    }
                }
            }

            return result;

    }
});