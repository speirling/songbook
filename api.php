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
                $filename = PLAYLIST_DIRECTORY.'/'.$post_data['playlist_name'].'.playlist';
                if(file_exists($filename)) {
                	$thisPlaylistContent = simplexml_load_file($filename);

                	$result = sbk_convert_simpleXML_playlist_to_json_string($thisPlaylistContent);
                	return '{"success": true, "data": '.$result.'}';
                } else {
                	return '{"success": false, "message": "playlist ['.$playlist_name.'] does not exist"}';
               	}
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
                return '{"success": false, "message": "no playlist nmme specified"}';
            }
        break;

        case 'get_available_songs':
            if(array_key_exists('search_string', $post_data)) {
                $search_string = $post_data['search_string'];
                if($search_string === '') {
                    $search_string = false;
                }
            } else {
                $search_string = false;
            }
            $all_songs = sbk_get_all_songs($search_string);
            return '{"success": true, "data": {"songs": '.$all_songs.'}}';
        break;

        case 'get_available_playlists':
            $all_playlists = '';
            $directoryList = scandir(PLAYLIST_DIRECTORY);
            foreach($directoryList as $filename) {
                if(!is_dir($filename)) {
                    $path_parts = pathinfo($filename);
                    if($path_parts['extension'] === 'playlist' && $path_parts['filename'] != '') {
                        $thisPlaylistContent = simplexml_load_file(PLAYLIST_DIRECTORY.'/'.$path_parts['filename'].'.playlist');
    		            $all_playlists = $all_playlists.'{"filename": "'.$path_parts['filename'].'", "title": "'.$thisPlaylistContent['title'].'", "act": "'.$thisPlaylistContent['act'].'"}, ';
                    }
                }
            }
            $all_playlists = substr($all_playlists, 0, -2);
            return '{"success": true, "data": {"playlists": ['.$all_playlists.']}}';
        break;

    }
}
?>
