<?php
require_once('admin/configure.inc.php');

if(array_key_exists('action', $_POST)) {
   echo process_post($_POST);
} else {
    echo '{error: "no action specified"}';
}

function process_post($post_data) {
    switch($post_data['action']) {
        case 'get_playlist':
            if(array_key_exists('playlist_name', $post_data)) {
                $playlist_json = get_playlist_json($post_data['playlist_name']);
                return '{"success": true, "data": '.$playlist_json.'}';
            } else {
                return '{"success": false, "message": "no playlist specified"}';
            }
        break;

        case 'update_playlist':
            if(array_key_exists('playlist_name', $post_data)) {
                if(array_key_exists('playlist_data', $post_data)) {
                    return sbk_update_playlist_file($post_data['playlist_name'], $post_data['playlist_data']);
                } else {
                    return '{"success": false, "message": "no playlist_data given"}';
                }
            } else {
                return '{"success": false, "message": "no playlist specified"}';
            }
        break;

        case 'get_available_songs':
            $all_songs = sbk_get_all_songs();
            return '{"success": true, "data": {"songs": '.$all_songs.'}}';
        break;


    }
}

function get_playlist_json($playlist_name) {
    $filename = PLAYLIST_DIRECTORY.'/'.$playlist_name.'.playlist';
    if(file_exists($filename)) {
    	$thisPlaylistContent = simplexml_load_file($filename);
    	$result = sbk_convert_simpleXML_playlist_to_json_string($thisPlaylistContent);
    	//p($result);
    	return $result;
    } else {
    	return '{error: "playlist does not exist"}';
   	}
}
?>
