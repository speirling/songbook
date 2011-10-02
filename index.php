<?php
require_once('admin/configure.inc.php');
//require_once("dompdf/dompdf_config.inc.php");
require_once("wkhtml2pdf_integration.php");

error_log("******************************* Run songbook index *******************************");
$songBookContent = "";

if(array_key_exists('action', $_GET)) {
	$action = $_GET['action'];
} else {
    $action = 'list';
}

$STANDARD_JAVASCRIPTS[] = URL_TO_ACRA_SCRIPTS."/js/jquery.contextMenu/jquery.contextMenu.js";
$STANDARD_JAVASCRIPTS[] = "index.js";
$page_title = $action.'[playlists]';
$display = '';

$menu = '';
$menu = $menu.'<ul class="menu">';
$menu = $menu.'<li><a href="?action=listAllSongs">List all songs</a></li> ';
$menu = $menu.'<li><a href="?action=index">index of all songs</a></li> ';
$menu = $menu.'<li><a href="?action=listAllPlaylists">List all playlists</a></li> ';
$menu = $menu.'<li><a href="?action=addNewPlaylist">Add a new playlist</a></li> ';
$menu = $menu.'<li><a href="?action=editSong">Add new song</a></li> ';
$menu = $menu.'<li><a href="?action=listAllSongs">List all songs</a></li> ';
$menu = $menu.'<li><a href="?action=index">index of all songs</a></li> ';
$menu = $menu.'</ul>';

switch ($action) {
    case 'listAllSongs':
        $display = $display.$menu;

        $display = $display.'<h1>List of songs in the database:</h1>';
        $display = $display.'<div id="available-songs" class="main-list"></div>';

    break;

    case 'index':
        if(array_key_exists('playlist', $_GET)) {
            $playlist = $_GET['playlist'];
        } else {
            $playlist = false;
        }
        $display = $display.$menu;
        if($playlist) {
            $display = $display.'<h1>Index of '.$playlist.' playlist:</h1>';
            $thisPlaylistContent = simplexml_load_file(PLAYLIST_DIRECTORY.'/'.$playlist.'.playlist');
            $ID_array = sbk_getIDarray($thisPlaylistContent);
        } else {
            $display = $display.'<h1>Index of all songs in the database:</h1>';
            $ID_array = sbk_getIDarray();
        }

        $display = $display.'<div class="song-index">';
        $display = $display.sbk_generate_index($ID_array);
        $display = $display.'</div>';
    break;

    case 'listAllPlaylists':
    default:
        $display = $display.$menu;

        $display = $display.'<h1>List of playlists:</h1>';
        $directoryList = scandir(PLAYLIST_DIRECTORY);
        $display = $display.'<ul class="playlist-list">';
        foreach($directoryList as $filename) {
            if(!is_dir($filename)) {
                $path_parts = pathinfo($filename);
                if($path_parts['extension'] === 'playlist' && $path_parts['filename'] != '') {
                    $display = $display.'<li><a href="?action=displayPlaylist&playlist='.$path_parts['filename'].'">';
                    $thisPlaylistContent = simplexml_load_file(PLAYLIST_DIRECTORY.'/'.$path_parts['filename'].'.playlist');
		            $display = $display.$thisPlaylistContent['title'];
		            $display = $display.'</a></li>';
                }
            }
        }
        $display = $display.'</ul>';
    break;

    case 'addNewPlaylist':
        if(array_key_exists('filename', $_POST)) {
            sbk_create_blank_playlist($_POST['filename']);
            $display = $display.acradisp_javascriptRedirectTo('?action=displayPlaylist&playlist='.$_POST['filename']);
        } else {
            $display = $display.$menu;

            $display = $display.'<h1>Add a new playlist:</h1>';
            $display = $display.'<form id="filename-new-playlist" action="#" method="post">';
            $display = $display.'<input type="text" name="filename" />';
            $display = $display.'</form>';
        }
    break;

    case 'displayPlaylist':
        $playlist = $_GET['playlist'];
        if (array_key_exists('update', $_POST)) {
            switch ($_POST['update']) {
            case "PlaylistAddListOfSongs":
                //couldn't get checkboxes as an array
                $song_id_array = array();
                $sets = array();
                foreach ($_POST as $key => $value) {
                    if(strstr($key, "song_")) {
                        $set = str_replace('set_','',preg_filter('/_song_.*/','',$key));
                        if(!in_array($set, $sets)) {
                            $sets[] = $set;
                        }
                        $song_id_array[$set][] = $value;
                    }
                }
                sbk_add_songs_to_playlist($song_id_array, $sets, $playlist) ;
            break;
            case "replaceList":
                $playlistContent = sbk_convert_list_to_playlistXML($_POST['playlist_input']);
                $playlistContent->saveXML(PLAYLIST_DIRECTORY.'/'.$playlist.'.playlist');
            break;
            }
        } elseif (array_key_exists('addNewSet', $_GET)) {

        }
        $display = $display.$menu;
        $display = $display.'<ul class=menu>';
        $display = $display.'<li><a href="#" id="add-new-set" playlist="'.$playlist.'">Add a new set</a></li> ';
        $display = $display.'<li><a href="?action=pdfPlaylist&playlist='.$playlist.'">pdf</a>';
        $display = $display.'<li><a href="?action=index&playlist='.$playlist.'">Show an index of the songs in this playlist</a>';
        $display = $display.'</ul>';

        $display = $display.'<table class="displayPlaylist"><tr><td><span class="holder">';
        $display = $display.'<h3>Playlist</h3>';
        $display = $display.'<form id="playlistForm" method="post"><a href="#" id="savePlaylist">Save</a><input type="hidden" name="update" value="replaceList" /><textarea id="playlist_input" name="playlist_input" style="display:none;"></textarea></form>';
        $display = $display.'<div id="playlist-holder">';
        $display = $display.sbk_playlist_as_html($playlist);
        $display = $display.'</div>';
        $display = $display.'</span></td><td><span class="holder">';
        $display = $display.'<h3>Available songs</h3>';
        $display = $display.'<select class="playlist-chooser">';
        $display = $display.'<option value="all">all songs</option>';
        $directoryList = scandir(PLAYLIST_DIRECTORY);
            foreach($directoryList as $filename) {
                if(!is_dir($filename)) {
                    $path_parts = pathinfo($filename);
                    if($path_parts['extension'] === 'playlist' && $path_parts['filename'] != '') {
                        $display = $display.'<option value="'.$path_parts['filename'].'">';
                        $thisPlaylistContent = simplexml_load_file(PLAYLIST_DIRECTORY.'/'.$path_parts['filename'].'.playlist');
    		            $display = $display.$thisPlaylistContent['title'];
    		            $display = $display.'</option>';
                    }
                }
            }
        $display = $display.'</select>';
        $display = $display.'<div id="available-songs"></div>';
        $display = $display.'</span></td></tr></table>';

    break;

    case 'displaySong':
        if (array_key_exists('update',$_POST)) {
            switch ($_POST['update']) {
            case "addNewSong":
                //a new song has been added - submit to database before displaying
                $value_array = acradb_convert_POST_data_into_recordValueArray($_POST, SBK_TABLE_NAME, SBK_DATABASE_NAME);
                //p($value_array);
                $updatequery = acradb_generate_insert_query_from_value_array($value_array, SBK_TABLE_NAME);

                $result = acradb_get_query_result($updatequery, SBK_DATABASE_NAME);
                $id = mysql_insert_id();
                if(array_key_exists('playlist', $_POST)) {
                    sbk_add_songs_to_playlist(array($id), $_POST['playlist']);
                } else {
                }
            break;

            case "editExistingSong":
                $id = $_POST['display_id'];
                $value_array = acradb_convert_POST_data_into_recordValueArray($_POST, SBK_TABLE_NAME, SBK_DATABASE_NAME);
                $updatequery = acradb_generate_update_query_from_value_array($value_array, SBK_TABLE_NAME, 'id');
                $result = acradb_get_query_result($updatequery, SBK_DATABASE_NAME);
            break;
            }
        } else {
            $id = str_replace('id_', '', $_GET['id']);
        }
        $display = $display.'<ul class=menu>';
        $display = $display.'<li><a href="?action=displaySong&id='.($id - 1).'">&laquo; Previous</a></li>';
        $display = $display.'<li><a href="?action=editSong&id='.$id.'">Edit this song</a></li>';
        $display = $display.'<li><a href="?action=pdfSong&id='.$id.'">.pdf</a></li>';
        $display = $display.'<li><a href="?action=displaySong&id='.($id + 1).'">Next &raquo;</a></li>';
        $display = $display.'</ul>';
        $display = $display.$menu;

        $this_record = sbk_get_song_record($id);
        $display = $display.sbk_song_html($this_record);
        $page_title = $this_record['title'].' - playlists';
    break;

    case 'pdfSong':
        $number_of_lyric_lines_per_page = 58;
        $id = $_GET['id'];

        $this_record = acradb_get_single_record(SBK_DATABASE_NAME, SBK_TABLE_NAME, SBK_KEYFIELD_NAME, $id);

        $display = '<html><head><title>'.$this_record['title'].'</title><link href="../pdf.css" rel="stylesheet" type="text/css" /></head><body class="pdf">';
        $display = $display.sbk_get_song_html($id);
        $display = $display.'</body></html>';

        $pdf = new WKPDF();
        $pdf->set_orientation('portrait');
        $pdf->set_html($display);
        $pdf->render();
        $pdf->output(WKPDF::$PDF_DOWNLOAD, $this_record['title'].".pdf");

    break;


    case 'pdfPlaylist':
        $number_of_lyric_lines_per_page = 65;
        $playlist = $_GET['playlist'];
        $playlistContent = simplexml_load_file(PLAYLIST_DIRECTORY.'/'.$playlist.'.playlist');

        $display = '<html><head><title>'.$playlistContent['title'].'</title><link href="../pdf.css" rel="stylesheet" type="text/css" /></head><body class="pdf">';
        $display = $display.sbk_convert_playlistXML_to_table($playlistContent);
        $display = preg_replace('/&nbsp;/', '&#160;', $display); //&nbsp; doesn't work in XML unless it's specifically declared.
        $display = $display.'</body></html>';

        $pdf = new WKPDF();
        $pdf->set_orientation('landscape');
        $pdf->set_html($display);
        $pdf->render();
        $pdf->output(WKPDF::$PDF_DOWNLOAD, $playlistContent['title'].".pdf");


    break;

    case 'editSong':
        $display = $display.'<form id="edit-song-form" action = "?action=displaySong" method="post">';

        if (array_key_exists('update',$_POST)) {
            switch ($_POST['update']) {
            case "addNewSong":
                //a new song has been added - submit to database before displaying
                $value_array = acradb_convert_POST_data_into_recordValueArray($_POST, SBK_TABLE_NAME, SBK_DATABASE_NAME);
                $updatequery = acradb_generate_insert_query_from_value_array($value_array, SBK_TABLE_NAME);

                $result = acradb_get_query_result($updatequery, SBK_DATABASE_NAME);
                $id = mysql_insert_id();
                if(array_key_exists('playlist', $_POST)) {
                    sbk_add_songs_to_playlist(array($id), $_POST['playlist']);
                } else {
                }
            break;

            case "editExistingSong":
                $id = $_POST['display_id'];
                $value_array = acradb_convert_POST_data_into_recordValueArray($_POST, SBK_TABLE_NAME, SBK_DATABASE_NAME);
                $updatequery = acradb_generate_update_query_from_value_array($value_array, SBK_TABLE_NAME, 'id');
                $result = acradb_get_query_result($updatequery, SBK_DATABASE_NAME);
            break;
            }
        } elseif(array_key_exists('id', $_GET)) {
            $id = $_GET['id'];
        } else {
            $id = false;
        }

        if($id) {
            $display = $display.'<input type="hidden" name="update" id="update" value="editExistingSong"></input>';
            $display = $display.'<input type="hidden" name="display_id" id="display-id" value="'.$id.'"></input>';
            $this_record = acradb_get_single_record('music_admin', 'lyrics', 'id', $id);
            $display = $display.'<ul class=menu>';
            $display = $display.'<li><a href="#" onclick="jQuery(\'#edit-song-form input#display-id\').val('.($id-1).'); jQuery(\'#edit-song-form\').attr(\'action\',\'?action=editSong\').submit();">Edit Previous</a></li>';
            $display = $display.'<li><a href="?action=displaySong&id='.$id.'">Cancel edit</a></li>';
            $display = $display.'<li><a href="#" onclick="jQuery(\'#edit-song-form input#display-id\').val('.($id+1).'); jQuery(\'#edit-song-form\').attr(\'action\',\'?action=editSong\').submit();">Edit Next</a></li>';
            $display = $display.'</ul>';
            $display = $display.$menu;

            $display = $display.'<h1>Edit song</h1>';
            $display = $display.'<div class="song_id">Song ID: ['.$id.']</div>';
            $display = $display.'<input type="hidden" name="id" id="id" value="'.$id.'"></input>';
        } else {
            $display = $display.'<input type="hidden" name="update" id="update" value="addNewSong"></input>';
            $display = $display.'<h1>Add a new song</h1>';
            if(array_key_exists('playlist', $_GET)) {
                $display = $display.'<h2>to playlist [' . $_GET['playlist'] . ']</h2>';
            }
            $display = $display.$menu;
            $this_record = array(
                'title' => '',
                'performed_by' => '',
                'written_by' => '',
                'content' => '',
                'meta_tags' => '',
                'original_filename' => '',
                'base_key' => ''
            );
        }

        if(array_key_exists('playlist', $_GET)) {
            $display = $display.'<input type="hidden" name="playlist" id="update" value="'.$_GET['playlist'] .'"></input>';
        }
        $display = $display.'<div class="title">title: <input type="text" name="title" id="title" size=80 value="'.$this_record['title'].'"></input></div>';
        $display = $display.'<div class="performed_by"><span class=label>performed by: </span><input type="text" name="performed_by" id="performed_by" size=80 value="'.$this_record['performed_by'].'"></input></div>';
        $display = $display.'<div class="written_by"><span class=label>written by: </span><input type="text" name="written_by" id="written_by" size=80 value="'.$this_record['written_by'].'"></input></div>';
        $display = $display.'<div class="base_key"><span class=label>base_key: </span><input type="text" name="base_key" id="base_key" size=10 value="'.$this_record['base_key'].'"></input></div>';
        $display = $display.'<div class="content">content: <a id="remove_linebreaks" href="#">Remove double linebreaks</a><br /><textarea name="content" id="content" cols=80 rows=20>'.$this_record['content'].'</textarea></div>';
        $display = $display.'<div class="meta_tags"><input type="text" name="meta_tags" id="meta_tags" size=80 value="'.$this_record['meta_tags'].'"></input></div>';
        $display = $display.'<div class="original_filename"><input type="text" name="original_filename" id="original_filename" size=80 value="'.$this_record['original_filename'].'"></input></div>';
        $display = $display.'<input type=submit value="Save changes" />';
        $display = $display.'</form>';
    break;
}

$display = acradisp_standardHTMLheader($page_title, array('index.css'), $STANDARD_JAVASCRIPTS).$display.'</body></html>';

echo $display;



?>
