/*global jQuery document */

jQuery(document).ready(function() {
	var lyrics_panels = jQuery('.sbk-lyrics-panel');

	if (lyrics_panels.length > 0) {
	    new SBK.ChordEditor(jQuery('.sbk-lyrics-panel')).render();
	}
	
	jQuery('select').select2();
});
