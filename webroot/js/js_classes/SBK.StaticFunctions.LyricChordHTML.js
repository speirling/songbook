SBK.StaticFunctions.LyricChordHTML = {
    song_content_to_html: function (text_with_inline_chords, base_key, display_key, capo) {
        var self = this, html;

        if (typeof(base_key) !== 'undefined' && typeof(display_key) !== 'undefined' && base_key !== '' && display_key !== '') {
            
            transpose_object = {
                transpose: true,
                base_key: base_key, 
                display_key: display_key, 
                capo: capo    
            };
        } else {
            transpose_object = {transpose: false};
        }

        html = text_with_inline_chords.replace(/&([^#n])/g, '&#38;$1');
    
        html = html.replace(/\n/g,'</span></div><div class="line"><span class="text">');
        //empty lines - put in a non-breaking space so that they don't collapse?
        html = html.replace(/<div class="line"><span class="text">[\s]*?<\/span><\/div>/g, '<div class="line"><span class="text">&nbsp;</span></div>');
        // chords that are close together - [Am][D] etc ... even [Am]  [D].... should be separated by characters equal in width to the chord (or by margin in css?)
        //I'll mark these kinds of chords with "!" so that I can set their class in  chord_replace_callback()
        // IN PHP "\h" matches a 'horizontal whitespace' character so the expression '/\](\h*?)\[/' should find relevant chords
        html = html.replace(/\](\s*?)\[/g, '!]$1[');
        html = html.replace(/\[(.*?)\]/g, function (match) {return SBK.StaticFunctions.LyricChordHTML.chord_replace_callback(transpose_object, match);});
        html = html.replace(/#<span class="chord">([^<]*?)\/([^<]*?)<\/span>#/g,'<span class="chord">$1<span class="bass_note_modifier separator">/</span><span class="bass_note_modifier note">$2</span></span>');
        //html = html.replace(/&nbsp;/g, '&#160;'); //&nbsp; doesn't work in XML unless it's specifically declared.
        html = html.replace(/\{score:(.*?)\}/g, '<img src="/songbook/score/$1" />');
        html = '<div class="line"><span class="text">' + html;
        
        return html;
    },
    
    chord_replace_callback: function (transpose_object, match) {
        var chord, fullsize_class = '';

        chord = match;
        chord = chord.replace('[', '');
        chord = chord.replace(']', '');

        if(chord.indexOf('!') > -1) {
            //This is one of those chords followed by whitepace, that needs to be set to greater than 0 space.
            //I'll use a class for that
            fullsize_class = " full-width";
            chord = chord.replace('!', '');
        }

        if (transpose_object.transpose === true) {
            chord = SBK.StaticFunctions.transpose_chord(chord, transpose_object.base_key, transpose_object.display_key, transpose_object.capo);
        }

        return '</span><span class="chord' + fullsize_class + '">' + chord + '</span><span class="text">';
    }
}