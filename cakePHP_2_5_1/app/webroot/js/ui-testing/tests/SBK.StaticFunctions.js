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
        deepEqual(SBK.StaticFunctions.transpose_chord('G', 'F#', 'A', 0), 'A#', 'target key A');
        deepEqual(SBK.StaticFunctions.transpose_chord('G#', 'D', 'F', 0), 'B');
        deepEqual(SBK.StaticFunctions.transpose_chord('Ab', 'D', 'F', 0), 'B', 'target key F');
        deepEqual(SBK.StaticFunctions.transpose_chord('Ab', 'A', 'C', 0), 'B', 'target key C');
        deepEqual(SBK.StaticFunctions.transpose_chord('Ab', 'F#', 'A', 0), 'B', 'target key A');
        deepEqual(SBK.StaticFunctions.transpose_chord('A', 'D', 'F', 0), 'C');
    });
});
