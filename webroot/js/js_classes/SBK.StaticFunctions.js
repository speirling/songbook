/* jQuery plugins */

$.fn.insertAtCaret = function(text, set_caret_position_before_or_after) { // I was unable to position the caret inside the inserted text, so I decided to positionion it after one half of the text and before the second half
    return this.each(function() {
        return SBK.StaticFunctions(this, text, set_caret_position_before_or_after);
    });
};


/* SBK static functions */
SBK.StaticFunctions = {
    insert_at_caret: function (container, text, set_caret_position_before_or_after) {
        var offset;

        if (typeof(set_caret_position_before_or_after) !== 'undefined' && set_caret_position_before_or_after === 'before') {
            offset = 0;
        } else {
            offset = text.length;
        }
        if (document.selection && container.tagName == 'TEXTAREA') {  //IE textarea
            container.focus();
            sel = document.selection.createRange();
            sel.text = text;
            container.focus();
        } else if (container.selectionStart || container.selectionStart == '0') { //MOZILLA textarea
            startPos = container.selectionStart;
            endPos = container.selectionEnd;
            scrollTop = container.scrollTop;
            container.value = container.value.substring(0, startPos) + text + container.value.substring(endPos, container.value.length);
            container.focus();
            container.selectionStart = startPos + offset;
            container.selectionEnd = startPos + offset;
            container.scrollTop = scrollTop;
        } else {
            //non textarea
            var selectionObject = window.getSelection();
            var range = selectionObject.getRangeAt(0);
            text_node = document.createTextNode(text);
            range.insertNode(text_node);
            range.setStart(text_node, offset);
            range.setEnd(text_node, offset);
        }
    },

    replace_textbox_selection: function (range, replace_text) {
        old_content = range.container.value;
        old_scroll_top = jQuery(range.container).scrollTop();
        new_content = old_content.slice(0, range.start) + replace_text + old_content.slice(range.end - old_content.length);

        range.container.value = new_content;
        //Now that the old selection is deleted, set selection to the start...
        range.end = range.start + replace_text.length;
        range.container.setSelectionRange(range.start, replace_text.length);
        window.setTimeout(function () {
            jQuery(range.container).scrollTop(old_scroll_top);
        }, 1);
        
        return range;
    },

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
            upper_note = upper_note + note.substr(1, 1);
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
        } else if (use_sharps === true) {
            new_note = SBK.Constants.VALUE_NOTE_ARRAY_SHARP[new_note_number];
        } else {
            new_note = SBK.Constants.VALUE_NOTE_ARRAY_FLAT[new_note_number];
        }
        if(lowercase) {
            new_note = SBK.StaticFunctions.note_to_lower(new_note);
        }

        return new_note;
    },
    
    transpose_chord: function (chord, base_key, target_key, capo) {
        var chord_note, second_char, modifier_start, chord_modifier, key_conversion_value, new_chord, bass_key, slash_position, old_bass_key, new_bass_key;

        if(chord === '-') { //this means you've a typo in your lyrics... a blank chord left in!
            return '-';
        }
        if(base_key === null || base_key === '') {
            throw new Exception("SBK.StaticFunctions.transpose_chord() :: no base key passed");
        }
        if(target_key === null || target_key === '') {
            throw new Exception("SBK.StaticFunctions.transpose_chord() :: no target key passed");
        }
        key_conversion_value = SBK.Constants.NOTE_VALUE_ARRAY[target_key] - SBK.Constants.NOTE_VALUE_ARRAY[base_key];
        key_conversion_value = key_conversion_value - capo;
        if (typeof(capo) === 'undefined') {
           capo = 0;
        }
        if(chord.substring(0, 1) === '/') { // "Born to run" has standalone bass notes... [/a] [/g#] [/g] etc.
            new_chord = '';
            chord_modifier = chord;
        } else {
            chord_note = chord.substring(0, 1);
            second_char = chord.substring(1, 2);
            modifier_start = 1;
            if (second_char === '#' || second_char == 'b') {
                chord_note = chord_note + second_char;
                modifier_start = 2;
            }
            chord_modifier = chord.substring(modifier_start);
            if (target_key.substring(1, 2) === '#' || target_key === 'D' || target_key === 'E' || target_key === 'A' || target_key === 'B') {
                new_chord = SBK.StaticFunctions.shift_note(chord_note, key_conversion_value, true);
            } else if (target_key.substring(1, 2) === 'b' || base_key === 'F') {
                new_chord = SBK.StaticFunctions.shift_note(chord_note, key_conversion_value, false);
            } else {
                new_chord = SBK.StaticFunctions.shift_note(chord_note, key_conversion_value);
            }
        }
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
        if (chord_string === '') {
            return result;
        } else if (chord_string === '-') {
            return result;
        } else {
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

            return result;
        }
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
            false;
        }
    },

    getUrlParam: function(strParamName) {
	 strParamName = escape(unescape(strParamName));
	 //iPad1 doesn't like URL object nor URLSearchParams objects.
	 //nor decodeURIComponent
	 
     //var url = new URL(document.location);
     //alert(window.location.search.substring(1));
     var query = window.location.search.substring(1);
	 var vars = query.split('&');
	 var paramsJSON = {};
	 for (var i = 0; i < vars.length; i++) {
	    var pair = vars[i].split('=');
	    if(pair[0] == strParamName) {
	    return pair[1];
	    }
	 }
	 return null;

/*
      if(typeof(paramsJSON[strParamName])) != "undefined") {
        	return paramsJSON[strParamName];
      } else if (window.attr("nodeName")=="#document") {
	  	//document-handler
		if (window.location.search.search(strParamName) > -1 ){	
			qString = window.location.search.substr(1,window.location.search.length).split("&");
		}
			
	  } else if (typeof(window.attr("src")) != "undefined") {
	  	var strHref = window.attr("src")
	  	if ( strHref.indexOf("?") > -1 ){
	    	var strQueryString = strHref.substr(strHref.indexOf("?")+1);
	  		qString = strQueryString.split("&");
	  	}
	  } else if (typeof(window.attr("href")) != "undefined") {
	  	var strHref = window.attr("href")
	  	if ( strHref.indexOf("?") > -1 ){
	    	var strQueryString = strHref.substr(strHref.indexOf("?")+1);
	  		qString = strQueryString.split("&");
	  	}
	  } else {
	  	return null;
	  }
	  
	  if (qString==null) return null;
	  
	  for (var i=0;i<qString.length; i++){
			if (escape(unescape(qString[i].split("=")[0])) == strParamName){
				returnVal.push(qString[i].split("=")[1]);
			}
			
	  }
	  
	  if (returnVal.length==0) return null;
	  else if (returnVal.length==1) return returnVal[0];
	  else return returnVal;
	  */
	 },

	 setUrlParam: function(windowLocationSearchString, strParamName, value) {
								      /*
									  strParamName = escape(unescape(strParamName));
								      var url = new URL(document.location);
								      
							          url.searchParams.set(strParamName, value);
							          */
         //The following works with iPad 1							          
							          
         var parameter_is_used = false;
         if(windowLocationSearchString.length > 0)  {
	         var outPutWindowsLocationSearchString = "";
	         var query = windowLocationSearchString.substring(1); //querystring without the ?
		     var vars = query.split('&');
			 for (var i = 0; i < vars.length; i++) {
			    if (i > 0) {
			        outPutWindowsLocationSearchString = outPutWindowsLocationSearchString + "&";
			    }
			    var pair = vars[i].split('=');
			    if(pair[0] == strParamName) {
			        parameter_is_used = true;
			        outPutWindowsLocationSearchString = outPutWindowsLocationSearchString + pair[0] + "=" + value;
			    } else {
			        outPutWindowsLocationSearchString = outPutWindowsLocationSearchString + pair[0] + "=" + pair[1];
			    }
			 }
			 if(parameter_is_used === false) {
	             outPutWindowsLocationSearchString = outPutWindowsLocationSearchString + "&" + strParamName + "=" + value;
	         }
	     } else {
	         var outPutWindowsLocationSearchString = strParamName + "=" + value;
	     }
	     
		 return "?" + outPutWindowsLocationSearchString;
	          
	 },
	 
	 rounded_window_size: function(rounding_limit) {
	    if (typeof(rounding_limit) == null) {
	        rounding_limit = 10;
	    }
	    return {
	       "width" : Math.round($(window).width() / rounding_limit) * rounding_limit,
           "height" : Math.round($(window).height() / rounding_limit) * rounding_limit
        }
	 },
	 
	 
	 set_window_size_in_URL: function (current_window) {
        var self = this;
 
		cw = self.rounded_window_size(10).width;
		ch = self.rounded_window_size(10).height;
		
		var vw = self.getUrlParam("vw");
		var vh = self.getUrlParam("vh");
		var changed_dimensions = false;
		var new_vw = vw;
		var new_vh = vh;

		if (vw === null || vh === null) {
		    new_vw = cw;
		    new_vh = ch;
		    changed_dimensions = true;
		}  else {
		    if (cw !== Math.round(vw / 10) * 10) {
		        new_vw = cw;
		        changed_dimensions = true;
		    }
		    if (ch !== Math.round(vh / 10) * 10) {
		        new_vh = ch;
		        changed_dimensions = true;
		     }
		}
	  
		if (changed_dimensions === true) {
		    //var url = new URL(document.location);
		    
		    //url.searchParams.set('vw', new_vw);
		    //url.searchParams.set('vh', new_vh);
		    //document.location = url;
		    
		    var windowLocationSearchString = window.location.search;
		    console.log(windowLocationSearchString);
		    windowLocationSearchString = self.setUrlParam(windowLocationSearchString, 'vw', new_vw);
		    windowLocationSearchString = self.setUrlParam(windowLocationSearchString, 'vh', new_vh);
 
			window.location.href = windowLocationSearchString;
		    return true;
		} else {
		    return false;
		}
                
    },

	make_hideable_panel: function (panel, button_showtext, button_hidetext) {
        var panel = jQuery(panel);
		var button = jQuery('<span class="button show-menu" style="display: none">' + button_showtext + '</span>');
		
		jQuery(panel).before(button).hide(); //panel might be initially visible, so that select2 picks up widths properly
		jQuery(panel).css('visibility', 'visible'); //panel might be initially be set to visiility:hidden, so that select2 picks up widths properly veven while it's not visible
		button.click(function(){
            	if(panel.is(":visible")) { 
            		panel.hide();
            		button.html(button_showtext);
            	} else {
            		panel.show();
            		button.html(button_hidetext);
            	}
            }).show();
	}
};