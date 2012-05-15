<?php
require_once('admin/configure.inc.php');
echo return_playlist_json('Copy of Clare.playlist');
/*
if(array_key_exists('action', $_POST)) {
    process_post($_POST)
} else {
    echo '{error: "no action specified"}';
}

function process_post($post_data) {
    switch($post_data('action')) {
        case: 'get_playlist':
            if(array_key_exists('filename', $post_data)) {
                return_playlist_json($post_data['filename'])
            } else {
                return '{error: "no playlist specified"}';
            }
        break;


    }
}
*/
function return_playlist_json($filename) {
    $path_parts = pathinfo($filename);
    p($path_parts);
    if($path_parts['extension'] === 'playlist' && $path_parts['filename'] != '') {
        $playlist = $post_data['playlist'];
        $filename = PLAYLIST_DIRECTORY.'/'.$path_parts['filename'].'.playlist';
        p($filename);
        if(file_exists($filename)) {
            p('file exists');
        	$thisPlaylistContent = simplexml_load_file($filename);
        	p($thisPlaylistContent);
        	return json_encode($thisPlaylistContent);
        } else {
        	return '{error: "playlist does not exist"}';
       	}
    } else {
        return '{error: "invalid playlist filename"}';
    }
}
?>
