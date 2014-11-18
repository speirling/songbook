SBK.ChordEditor = SBK.Class.extend({
	init: function (parent_container, callback) {
		var self = this;

		self.container = jQuery('<div class="chord-editor"></div>').prependTo(parent_container.parent()).hide();
        self.callback = callback;
        self.chord_object = {};
        self.bass_note_requested = false;
        self.initial_value = '';
        self.display_mode = 'static';
	},

    render: function () {
        var self = this;

        self.input = jQuery('<div contenteditable="true" class="chord-editor-input"></div>').appendTo(self.container);
        self.button_containermodifiers = jQuery('<div class="container container-modifiers"></div>').appendTo(self.container);
        self.button_containernotes = jQuery('<div class="container container-notes"></div>').appendTo(self.container);
        self.buttons = {};
        self.buttons.key_note = jQuery('<div class="button button-note-type button-note-type-key selected"><span>key</span></div>').appendTo(self.button_containernotes).click(function () { 
            self.bass_note_requested = false;
            self.buttons.key_note.addClass('selected');
            self.buttons.bass_note.removeClass('selected');
        });
        
        self.buttons.bass_note = jQuery('<div class="button button-note-type button-note-type-bass"><span>bass</span></div>').appendTo(self.button_containernotes).click(function () { 
            self.bass_note_requested = true;
            self.buttons.key_note.removeClass('selected');
            self.buttons.bass_note.addClass('selected');
        });
        self.buttons.note_c = jQuery('<div class="button button-note button-note-white button-note-c"><span>c</span></div>').appendTo(self.button_containernotes).click(function () { 
            self.handle_note_button('C'); 
        });
        self.buttons.note_csharp = jQuery('<div class="button button-note button-note-black button-note-csharp"><span>c#</span></div>').appendTo(self.button_containernotes).click(function () { 
            self.handle_note_button('C#');  
        });
        self.buttons.note_d = jQuery('<div class="button button-note button-note-white button-note-d"><span>d</span></div>').appendTo(self.button_containernotes).click(function () { 
            self.handle_note_button('D'); 
        });
        self.buttons.note_dsharp = jQuery('<div class="button button-note button-note-black button-note-dsharp"><span>d#</span></div>').appendTo(self.button_containernotes).click(function () { 
            self.handle_note_button('D#'); 
        });
        self.buttons.note_e = jQuery('<div class="button button-note button-note-white button-note-e"><span>e</span></div>').appendTo(self.button_containernotes).click(function () { 
            self.handle_note_button('E');  
        });
        self.buttons.note_f = jQuery('<div class="button button-note button-note-white button-note-f"><span>f</span></div>').appendTo(self.button_containernotes).click(function () { 
            self.handle_note_button('F'); 
        });
        self.buttons.note_fsharp = jQuery('<div class="button button-note button-note-black button-note-fsharp"><span>f#</span></div>').appendTo(self.button_containernotes).click(function () { 
            self.handle_note_button('F#'); 
        });
        self.buttons.note_g = jQuery('<div class="button button-note button-note-white button-note-g"><span>g</span></div>').appendTo(self.button_containernotes).click(function () { 
            self.handle_note_button('G'); 
        });
        self.buttons.note_gsharp = jQuery('<div class="button button-note button-note-black button-note-gsharp"><span>g#</span></div>').appendTo(self.button_containernotes).click(function () { 
            self.handle_note_button('G#'); 
        });
        self.buttons.note_a = jQuery('<div class="button button-note button-note-white button-note-a"><span>a</span></div>').appendTo(self.button_containernotes).click(function () { 
            self.handle_note_button('A'); 
        });
        self.buttons.note_asharp = jQuery('<div class="button button-note button-note-black button-note-asharp"><span>a#</span></div>').appendTo(self.button_containernotes).click(function () { 
            self.handle_note_button('A#'); 
        });
        self.buttons.note_b = jQuery('<div class="button button-note button-note-white button-note-b"><span>b</span></div>').appendTo(self.button_containernotes).click(function () { 
            self.handle_note_button('B'); 
        });

        self.buttons.close = jQuery('<div class="button button-submit button-submit-close"><span>Set<span></div>').appendTo(self.button_containernotes).click(function () { self.close(); });
        self.buttons.backspace = jQuery('<div class="button button-submit button-submit-backspace"><span>Back<span></div>').appendTo(self.button_containernotes).click(function () { self.register_backspace(); });

        self.buttons.modifier_major = jQuery('<div class="button button-modifier button-modifier-major"><span>(major)</span></div>').appendTo(self.button_containermodifiers).click(function () { 
            self.chord_object.modifier = ''; 
            self.display_value();
        });
        self.buttons.modifier_minor = jQuery('<div class="button button-modifier button-modifier-minor"><span>minor</span></div>').appendTo(self.button_containermodifiers).click(function () { 
            self.chord_object.modifier = 'm';  
            self.display_value();
        });
        self.buttons.modifier_dominantseventh = jQuery('<div class="button button-modifier button-modifier-seventh"><span>dom 7</span></div>').appendTo(self.button_containermodifiers).click(function () { 
            self.chord_object.modifier = '7';  
            self.display_value();
        });
        self.buttons.modifier_minorseventh = jQuery('<div class="button button-modifier button-modifier-minor"><span>min 7</span></div>').appendTo(self.button_containermodifiers).click(function () { 
            self.chord_object.modifier = 'm7';  
            self.display_value();
        });
        self.buttons.modifier_diminished = jQuery('<div class="button button-modifier button-modifier-minor"><span>- (dim)</span></div>').appendTo(self.button_containermodifiers).click(function () { 
            self.chord_object.modifier = '-';  
            self.display_value();
        });
        self.buttons.modifier_augmented = jQuery('<div class="button button-modifier button-modifier-minor"><span>+ (aug)</span></div>').appendTo(self.button_containermodifiers).click(function () { 
            self.chord_object.modifier = '+';  
            self.display_value();
        });
        
        

        self.input.bind('keypress', function (event) {console.log('key pressed', event.charCode);
            self.handle_charCode(event.charCode);
            return false;
        });
    },

    open: function (x, y, chord_string) {
        var self = this;

        if (typeof(chord_string) === 'undefined') {
            chord_string = ''; 
        } 
        
        self.bass_note_requested = false;
        self.buttons.key_note.addClass('selected');
        self.buttons.bass_note.removeClass('selected');

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
       if(self.display_mode === 'floating') {
           self.container.hide();
       }
       if (self.get_value() !== self.initial_value) {
           self.callback(self.get_value());
       }
    },

    display_value: function (chord_string) {
        var self = this, chord_html = '';

        if(typeof(chord_string) !== 'undefined') {
            self.chord_object = SBK.StaticFunctions.parse_chord_string(chord_string);
        }
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
        var self = this, bass_note, note, key = false, cc, bass_note = false, bass_note_modifier = false, chord_text, char;

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
    },

    next_part: function (cc) { // determine how keypress should be determined - what part of the chord ar eyou in - not, modifier or bassnote
        var self = this, result = false, bass_note, note, key = false, cc, bass_note = false, bass_note_modifier = false, chord_text, char;

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
                    bass_note = '';
                } else {
                    bass_note = self.chord_object.bass_note;
                }
                if (bass_note.length === 0) { // first bass note character must be a key (lower case)
                    return 'bass_note';
                } else if (bass_note.length === 1) { //bass notes can only have two characters - a key and a flat or sharp
                    return 'bass_note_modifier';
                } else {
                    return false;
                }
            }
            console.log(result);
            return result;

    }
});