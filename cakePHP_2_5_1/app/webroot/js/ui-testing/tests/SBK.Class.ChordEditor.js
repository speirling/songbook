/*global SBK, jQuery, document, module, deepEqual, ok, test, asyncTest, start */

jQuery(document).ready(function () {
    module('SBK.Class.ChordEditor');
    
    test('init', 7, function () {
        var outer_container, lyrics_container, test_object,callback;

        callback = function (v, range) {
            deepEqual(v, expected_v, 'v parameter is correct');
            deepEqual(range, range, 'v parameter is correct');
        };
        outer_container = jQuery('<div class="songlist-ChordEditor-1"></div>').appendTo('body');
        lyrics_container = jQuery('<div class="lyrics-container"></div>').appendTo(outer_container);
        
        test_object = new SBK.ChordEditor(lyrics_container, callback);
        test_object.render();
        deepEqual(jQuery('.chord-editor', outer_container).length, 1, 'container created');
        deepEqual(test_object.callback, callback, 'callback assigned');
        deepEqual(test_object.chord_object, {}, 'chord_object initialised');
        deepEqual(test_object.bass_note_requested, false, 'bass_note_requested initalised');
        deepEqual(test_object.initial_value, '', 'initial_value initialised');
        deepEqual(test_object.display_mode,'static', 'display_mode initialised');
        deepEqual(test_object.range, null, 'range initialised');
    });
});
