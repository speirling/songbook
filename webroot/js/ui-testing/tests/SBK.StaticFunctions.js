/*global SBK, jQuery, document, module, deepEqual, ok, test, asyncTest, start */

jQuery(document).ready(function () {
    module('SBK.StaticFunctions');

    test('transpose_chord', 14, function () {

        deepEqual(SBK.StaticFunctions.transpose_chord('D', 'D', 'F', 0), 'F');
        deepEqual(SBK.StaticFunctions.transpose_chord('D#', 'D', 'F', 0), 'F#');
        deepEqual(SBK.StaticFunctions.transpose_chord('E', 'D', 'F', 0), 'G');
        deepEqual(SBK.StaticFunctions.transpose_chord('F', 'D', 'F', 0), 'G#');
        deepEqual(SBK.StaticFunctions.transpose_chord('F#', 'D', 'F', 0), 'A');
        deepEqual(SBK.StaticFunctions.transpose_chord('F#m', 'D', 'F', 0), 'Am');
        deepEqual(SBK.StaticFunctions.transpose_chord('G', 'D', 'F', 0), 'Bb', 'target key F');
        deepEqual(SBK.StaticFunctions.transpose_chord('G', 'A', 'C', 0), 'Bb', 'target key C');
        deepEqual(SBK.StaticFunctions.transpose_chord('G', 'F#', 'A', 0), 'A#', 'chord = G, base_key = F#, target_key=  A, Capo = 0');
        deepEqual(SBK.StaticFunctions.transpose_chord('G#', 'D', 'F', 0), 'B');
        deepEqual(SBK.StaticFunctions.transpose_chord('Ab', 'D', 'F', 0), 'B', 'target key F');
        deepEqual(SBK.StaticFunctions.transpose_chord('Ab', 'A', 'C', 0), 'B', 'target key C');
        deepEqual(SBK.StaticFunctions.transpose_chord('Ab', 'F#', 'A', 0), 'B', 'target key A');
        deepEqual(SBK.StaticFunctions.transpose_chord('A', 'D', 'F', 0), 'C');
    });

    test('parse_chord_string', 5, function () {

        deepEqual(SBK.StaticFunctions.parse_chord_string('D'), {modifier: '', note: 'D'});
        deepEqual(SBK.StaticFunctions.parse_chord_string(''), {});
        deepEqual(SBK.StaticFunctions.parse_chord_string('-'), {});
        deepEqual(SBK.StaticFunctions.parse_chord_string('G#'), {modifier: '#', note: 'G'});
        deepEqual(SBK.StaticFunctions.parse_chord_string('Ebm'), {modifier: 'bm', note: 'E'});
    });
});
