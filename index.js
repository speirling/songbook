$(document).ready(function() {
	jQuery('#playlist-holder ul ul').sortable({
			connectWith: '.playlist ul'
		});

	jQuery('#playlist-holder>ul').sortable();

	jQuery('#playlist-holder li li, #allsongs li').contextMenu('context-menu', {
        'show lyrics': {
            click: function(element){ location.href = '?action=displaySong&id=' + element.attr('id').replace('id_', ''); }
        },
        'edit song': {
            click: function(element){ location.href = '?action=editSong&id=' + element.attr('id').replace('id_', ''); }
        }
      }
	);

	jQuery('#add-new-set').click(function () {
		var newSet = jQuery('<li class=\"set playlist\"><textarea class=\"set-title\" type=\"text\">New Set</textarea></li>');
		var newList = jQuery('<ul class=\"ui-sortable\"><li class=dummy>&nbsp;</li></ul>').sortable({
			connectWith: '.playlist ul'
		});
		jQuery('#playlist-holder>ul').append(newSet);
		newSet.append(newList);
	});

	jQuery('#savePlaylist').click(function() {
		jQuery('#playlist-holder textarea').each(function(){jQuery(this).html(jQuery(this).val());});
		jQuery('#playlist-holder li.song input.key').each(function(){jQuery(this).parent().attr('key', jQuery(this).val());}).remove();
		jQuery('#playlist-holder li.song input.singer').each(function(){jQuery(this).parent().attr('singer', jQuery(this).val());}).remove();
		jQuery('#playlist_input').val(jQuery('#playlist-holder').html());
		jQuery('#playlistForm').submit();
	});
    search_allsongs();
	jQuery('form#allsongsearch').submit(function () {
		search_allsongs();
		return false;
    });
	jQuery('.song-index .song').click(function () {
		location.href = '?action=displaySong&id=' + jQuery(this).attr('id');
	});
	jQuery('a#remove_linebreaks').click(function(){
		jQuery('textarea#content').html(jQuery('textarea#content').html().replace(/\n\n/gm, "\n"));
	})
});



function search_allsongs() {
	jQuery.get(
	    '/songbook/allsongs_filterlist.php',
	    {search_string: jQuery('#search_string').val()},
	    function (data) {
	    	jQuery('.all-song-list div#list').html(data);
	    	jQuery('.all-song-list #allsongsearch .number-of-records').html(jQuery('.all-song-list div#list .numberofrecords').html());
	    	if(jQuery('.displayPlaylist').length) {
        		jQuery('#playlist-holder ul ul, #allsongs ul').sortable({
        			connectWith: '.playlist ul'
        		});
    		}
    		if(jQuery('.listAllSongs').length) {
        		jQuery('#allsongs li').click(function () {
        			location.href = '?action=displaySong&id=' + jQuery(this).attr('id');
        		});
    		}
	    }
	);
}