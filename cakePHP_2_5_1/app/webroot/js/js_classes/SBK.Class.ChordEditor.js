SBK.ChordEditor = SBK.Class.extend({
	init: function (parent_container, callback) {
		var self = this;

		self.container = jQuery('<div class="chord-editor"></div>').appendTo(parent_container.parent()).hide();
        self.callback = callback;
        self.chord_object = {};
	},
    
    render: function () {
        var self = this;

        self.input = jQuery('<div contenteditable="true" class="chord-editor-input"></div>').appendTo(self.container);

        self.input.bind('keypress', function (event) {
           if (event.charCode === 0 ) {
               self.close();
           } else {
               self.key_press(event);
           }
           return false;
        });
    },
    
    open: function (x, y, chord_string) {
        var self = this;

        if (typeof(chord_string) === 'undefined') {
            self.chord_object = {}; 
        } else {
            self.chord_object = SBK.StaticFunctions.parse_chord_string(chord_string);
        }
        self.container.css({"top": y, "left": x, "position": "absolute"}).show();
        self.display_value();
        self.input.focus();
    },
    
    close: function () {
       var self = this;

       self.container.hide();
       self.callback(self.get_value());
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
    
    key_press: function (event) {
        var self = this, bass_note, note, key = false, cc, bass_note = false, bass_note_modifier = false, chord_text, char;
        
        cc = event.charCode;
        chord_text = self.get_value();
        //allow backspace to work
        if (cc === 0) { //backspace
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
            }
            if (typeof(self.chord_object.note) !== 'undefined') {
                delete self.chord_object.bass_note;
                delete self.chord_object.modifier;
                note = self.chord_object.note.slice(0, -1);
                if (note.length === 0) {
                    delete self.chord_object.note;
                } else {
                    self.chord_object.note = note;
                }
            }
        } else {
            if (typeof(self.chord_object.bass_note) === 'undefined') {
                if (typeof(self.chord_object.modifier) === 'undefined') {
                    //chord mode
                    if (typeof(self.chord_object.note) ==='undefined') {
                        chord_text = '';
                    } else {
                       chord_text = self.chord_object.note;
                    }
                    if (chord_text.length === 0) { //the first character must be a key (capital)
                        char = SBK.StaticFunctions.charCode_to_key_text(cc);
                        if(char) {
                            chord_text = char;
                        }
                    } else { //other notes
                        if (chord_text.length === 1) { // flat or sharp characters only allowed in second character
                            if(cc == '66' | cc == '98') { // B or b
                                chord_text = chord_text + 'b';
                            }
                            if(cc == '35') { // #
                                chord_text = chord_text + '#';
                            }
                        } else {
                            //modifier mode
                            if(cc == '47') { // "/" - bass note coming - change to bass note mode
                                self.chord_object.bass_note = null;
                            } else {
                                char = SBK.StaticFunctions.charCode_to_chord_modifier(cc);
                                if(char) {
                                    self.chord_object.modifier = char;
                                }
                            }
                        }
                    }
                    self.chord_object.note = chord_text;
                } else {
                    //modifier mode
                    if(cc == '47') { // "/" - bass note coming - change to bass note mode
                        self.chord_object.bass_note = null;
                    } else {
                        char = SBK.StaticFunctions.charCode_to_chord_modifier(cc);
                        if (char) {
                            self.chord_object.modifier = char;
                        }
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
                    bass_note = SBK.StaticFunctions.charCode_to_bass_note(cc);
                } else if (bass_note.length === 1) { //bass notes can only have two characters - a key and a flat or sharp
                    if (cc == '66' | cc == '98') { // B or b
                        chord_text = chord_text + 'b';
                        textarea.insertAtCaret('b');
                    }
                    if (cc == '35') { // #
                        chord_text = chord_text + '#';
                        textarea.insertAtCaret('#');
                    }
                }
                self.chord_object.bass_note = bass_note;
            }
        }
        self.display_value();
        return false;
    }
});