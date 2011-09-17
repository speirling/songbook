$(document).ready(function() {
	        
	jQuery('#playlist-holder ul ul').sortable({
			connectWith: '.playlist ul'
		});

	jQuery('#playlist-holder>ul').sortable();

	jQuery('#playlist-holder li li, #all-song-list li').contextMenu('context-menu', {
	    'show lyrics': {
	        click: function(element){ window.open('?action=displaySong&id=' + element.attr('id').replace('id_', '')); }
	    },
	    'edit song': {
	        click: function(element){window.open( '?action=editSong&id=' + element.attr('id').replace('id_', '')); }
	    },
	    'remove from playlist': {
	        click: function(element){ element.remove(); }
	    }
	});

	jQuery('#add-new-set').click(add_new_setlist(jQuery('#playlist-holder>ul')));

	jQuery('#savePlaylist').click(function() {
		jQuery('#playlist-holder textarea').each(function(){jQuery(this).html(jQuery(this).val());});
		jQuery('#playlist-holder li.song input.key').each(function(){jQuery(this).parent().attr('key', jQuery(this).val());}).remove();
		jQuery('#playlist-holder li.song input.singer').each(function(){jQuery(this).parent().attr('singer', jQuery(this).val());}).remove();
		jQuery('#playlist_input').val(jQuery('#playlist-holder').html());
		jQuery('#playlistForm').submit();
	});
	create_filter_list(jQuery('#available-songs'));

	jQuery('.song-index .song').click(function () {
		location.href = '?action=displaySong&id=' + jQuery(this).attr('id');
	});
	jQuery('a#remove_linebreaks').click(function (){
		jQuery('textarea#content').html(jQuery('textarea#content').html().replace(/\n\n/gm, "\n"));
	});
	
	jQuery('.playlist-chooser').change(function () {
		var value = jQuery(this).val(), container = jQuery('#available-songs');
		
		if (value === 'all') {
			create_filter_list(container);
		} else {
			display_songpicker_from_playlist(container, value);
		}
	});
});

function add_new_setlist(container) {
	var newSet = jQuery('<li class="set playlist"><textarea class="set-title" type="text">New Set</textarea></li>');
	var newList = jQuery('<ul class="ui-sortable"><li class=dummy>&nbsp;</li></ul>').sortable({
			connectWith: '.playlist ul'
	});
	container.append(newSet);
	newSet.append(newList);
}

function search_allsongs() {
	jQuery.get(
	    '/songbook/allsongs_filterlist.php',
	    {search_string: jQuery('#search_string').val()},
	    function (data) {
	    	jQuery('div#all-song-list').html(data);
	    	jQuery('#allsongsearch .number-of-records').html(jQuery('div#all-song-list .numberofrecords').html());
	    	if(jQuery('.displayPlaylist').length) {
        		jQuery('#playlist-holder ul ul, #allsongs ul').sortable({
        			connectWith: '.playlist ul'
        		});
    		}
    		if(jQuery('#allsongs').length) {
        		jQuery('#allsongs li').contextMenu('context-menu', {
        		    'show lyrics': {
        		        click: function(element){ window.open('?action=displaySong&id=' + element.attr('id').replace('id_', '')); }
        		    },
        		    'edit song': {
        		        click: function(element){ window.open('?action=editSong&id=' + element.attr('id').replace('id_', '')); }
        		    },
        		    'remove from playlist': {
        		        click: function(element){ element.remove(); }
        		    }
        		});
    		}
	    }
	);
}

function create_filter_list(container) {
	var html = '<form id="allsongsearch">' +
               '<span class="label">Filter: </span><input type="test" id="search_string" value="" />' + 
               '<span class="label">Number of songs displayed: </span><span class="number-of-records"></span>' + 
               '</form>' +
               '<div id="all-song-list"><span class="pleasewait">please wait...</span></div>' +
               '</div>';
	container.html(html);
	search_allsongs();	
	jQuery('form#allsongsearch').submit(function () {
		search_allsongs();
		return false;
    });
}


function display_songpicker_from_playlist(container, playlist) {
	jQuery.get(
		    '/songbook/display_playlist.php',
		    {playlist: playlist},
		    function (data) {
		    	container.html(data);
		    	if(jQuery('.displayPlaylist').length) {
	        		jQuery('ul', container).sortable({
	        			connectWith: '.playlist ul'
	        		});
	    		}
	    		if(jQuery('li', container).length) {
	        		jQuery('li', container).contextMenu('context-menu', {
	        		    'show lyrics': {
	        		        click: function(element){ window.open('?action=displaySong&id=' + element.attr('id').replace('id_', '')); }
	        		    },
	        		    'edit song': {
	        		        click: function(element){ window.open('?action=editSong&id=' + element.attr('id').replace('id_', '')); }
	        		    },
	        		    'remove from playlist': {
	        		        click: function(element){ element.remove(); }
	        		    }
	        		});
	    		}
		    }
		);
}

