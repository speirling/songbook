<?php
require_once('admin/configure.inc.php');
require_once("wkhtml2pdf_integration.php");

error_log("******************************* Run songbook index *******************************");
$songBookContent = "";

if(array_key_exists('action', $_GET)) {
	$action = $_GET['action'];
} else {
    $action = 'list';
}

$all_playlists = $all_playlists.']';

$STANDARD_JAVASCRIPTS[] = "index.js";
$page_title = $action.' (playlists)';
$display = '';

$menu = '';
$menu = $menu.'<ul class="menu main">';
$menu = $menu.'<li><a href="?action=listAllSongs">List all songs</a></li> ';
$menu = $menu.'<li><a href="?action=index">index of all songs</a></li> ';
$menu = $menu.'<li><a href="?action=listAllPlaylists">List all playlists</a></li> ';
$menu = $menu.'<li><a href="?action=displayPlaylist">Add a new playlist</a></li> ';
$menu = $menu.'<li><a href="?action=editSong">Add new song</a></li> ';
$menu = $menu.'</ul>';


$display = $display.$menu;

switch ($action) {
    case 'listAllSongs':
        $display = $display.'<h1>List of songs in the database:</h1>';
        $display = $display.'<div id="available-songs" class="main-list"></div>';
        $display = $display."
        <script type=\"text/javascript\">
            $(document).ready(function() {
				new SBK.SongFilterList(jQuery('#available-songs')).render();
            });
        </script>";

    break;

    case 'index':
        if(array_key_exists('playlist', $_GET)) {
            $playlist = $_GET['playlist'];
        } else {
            $playlist = false;
        }
        if($playlist) {
            $display = $display.'<h1>Index of '.$playlist.' playlist:</h1>';
            $thisPlaylistContent = simplexml_load_file(PLAYLIST_DIRECTORY.'/'.$playlist.'.playlist');
            $ID_array = sbk_getIDarray($thisPlaylistContent);
        } else {
            $display = $display.'<h1>Index of all songs in the database:</h1>';
            $ID_array = sbk_getIDarray();
        }

        $display = $display.sbk_generate_index($ID_array);
    break;

    case 'listAllPlaylists':
    default:
        $display = $display.'<h1>List of playlists:</h1>';
        $display = $display.'<div id="playlist-list"></div>';

        $display = $display."
        <script type=\"text/javascript\">
            $(document).ready(function() {
				new SBK.AllPlaylists(jQuery('#playlist-list')).render();
            });
        </script>";
    break;

    case 'displayPlaylist':
        $playlist = $_GET['playlist'];
        $page_title = "[".$playlist."]";

        $display = $display.'<ul class="menu local">';
        $display = $display.'<li><a href="?action=pdfPlaylist&playlist='.$playlist.'" target="new">table</a>';
        $display = $display.'<li><a href="?action=pdfPlaylist&playlist='.$playlist.'&pdf">table pdf</a>';
        $display = $display.'<li><a href="?action=emailPlaylist&playlist='.$playlist.'&pdf">email</a>';
        $display = $display.'<li><a href="?action=index&playlist='.$playlist.'">Show an index of the songs in this playlist</a>';
        $display = $display.'<li><a href="?action=playlistBook&playlist='.$playlist.'" target="new">Playlist as a book</a>';
        $display = $display.'<li><a href="?action=playlistBook&playlist='.$playlist.'&pdf">Playlist as a book pdf</a>';
        $display = $display.'</ul>';

        $display = $display.'<div id="playlist-holder"></div>';
        $display = $display.'<div id="song_picker"></div>';

        $display = $display."
        <script type=\"text/javascript\">
            $(document).ready(function() {
            	var playlist = new SBK.PlayList('".$playlist."', jQuery('#playlist-holder'));
            	playlist.render();

				var songpicker = new SBK.SongPicker(jQuery('#song_picker'));
				songpicker.link_to_playlist(playlist);
				songpicker.render();
            });
        </script>";
    break;

    case 'pdfSong':
        $number_of_lyric_lines_per_page = 58;
        $key = null;
        $singer = null;
        $capo = null;

        $id = $_GET['id'];
        if(array_key_exists('key', $_GET) && $_GET['key'] !== '') {
            $key = urldecode($_GET['key']);
        }
        if(array_key_exists('singer', $_GET) && $_GET['singer'] !== '') {
            $singer = urldecode($_GET['singer']);
        }
        if(array_key_exists('capo', $_GET) && $_GET['capo'] !== '') {
            $capo = (integer) $_GET['capo'];
        }

        $this_record = sbk_get_song_record($id);
        $display = sbk_song_html($this_record, $key, $singer, $capo);

        sbk_output_pdf($display, $this_record['title']);
    break;

    case 'pdfPlaylist':
        $playlist = $_GET['playlist'];
        $playlistContent = simplexml_load_file(PLAYLIST_DIRECTORY.'/'.$playlist.'.playlist');
        $display = sbk_convert_playlistXML_to_table($playlistContent);
        if($_GET['pdf']) {
            sbk_output_pdf($display, $playlistContent['title'], 'landscape');
        }
    break;

    case 'emailPlaylist':
        $playlist = $_GET['playlist'];
        $playlistContent = simplexml_load_file(PLAYLIST_DIRECTORY.'/'.$playlist.'.playlist');
        $display = sbk_convert_playlistXML_to_orderedlist($playlistContent, $show_key = TRUE, $show_capo = TRUE, $show_singer = TRUE, $show_id = FALSE, $show_writtenby = FALSE, $show_performedby = FALSE);
    break;

    case 'playlistBook':
        $playlist = $_GET['playlist'];
        $playlistContent = simplexml_load_file(PLAYLIST_DIRECTORY.'/'.$playlist.'.playlist');
        $ID_array = sbk_getIDarray($playlistContent);
        $display = '';

        $display = $display.'<div class="playlist-page">';
        $display = $display.sbk_convert_playlistXML_to_orderedlist($playlistContent, $show_key = TRUE, $show_capo = TRUE, $show_singer = TRUE, $show_id = TRUE, $show_writtenby = TRUE, $show_performedby = TRUE);
        $display = $display.'</div>';
        $display = $display.sbk_generate_index($ID_array);
        sort($ID_array);
        $display = $display.sbk_print_multiple_songs($ID_array);
        if(array_key_exists('pdf', $_GET)) {
          sbk_output_pdf($display, 'SongBook - '.$playlistContent['title']);
        }
    break;

    case 'displaySong':
        $key = null;
        $singer = null;
        $capo = null;
        if (array_key_exists('update',$_POST)) {
            switch ($_POST['update']) {
            case "addNewSong":
                //a new song has been added - submit to database before displaying
                $value_array = acradb_convert_POST_data_into_recordValueArray($_POST, SBK_TABLE_NAME, SBK_DATABASE_NAME);
                $updatequery = acradb_generate_insert_query_from_value_array($value_array, SBK_TABLE_NAME);

                $result = acradb_get_query_result($updatequery, SBK_DATABASE_NAME);
                $id = mysql_insert_id();
            break;

            case "editExistingSong":
                $id = $_POST['display_id'];
                $value_array = acradb_convert_POST_data_into_recordValueArray($_POST, SBK_TABLE_NAME, SBK_DATABASE_NAME);
                $updatequery = acradb_generate_update_query_from_value_array($value_array, SBK_TABLE_NAME, 'id');
                $result = acradb_get_query_result($updatequery, SBK_DATABASE_NAME);
            break;
            }
        } else {
            //$id = str_replace('id_', '', $_GET['id']); //why might this be needed?
            $id = $_GET['id'];
            if(array_key_exists('key', $_GET) && $_GET['key'] !== '') {
                $key = urldecode($_GET['key']);
            }
            if(array_key_exists('singer', $_GET) && $_GET['singer'] !== '') {
                $singer = urldecode($_GET['singer']);
            }
            if(array_key_exists('capo', $_GET) && $_GET['capo'] !== '') {
                $capo = (integer) $_GET['capo'];
            }
        }
        $display = $display.'<ul class="menu local">';
        $display = $display.'<li><a href="?action=displaySong&id='.($id - 1).'">&laquo; Previous</a></li>';
        $display = $display.'<li><a href="?action=editSong&id='.$id.'">Edit this song</a></li>';
        $display = $display.'<li><a href="?action=pdfSong&id='.$id;
        if(!is_null($key)) {
            $display = $display.'&key='.urlencode($key);
        }
        if(!is_null($singer)) {
            $display = $display.'&singer='.$singer;
        }
        if(!is_null($capo)) {
            $display = $display.'&capo='.$capo;
        }
        $display = $display.'">.pdf</a></li>';
        $display = $display.'<li><a href="?action=displaySong&id='.($id + 1).'">Next &raquo;</a></li>';
        $display = $display.'</ul>';

        $this_record = sbk_get_song_record($id);
        $display = $display.sbk_song_html($this_record, $key, $singer, $capo);
        $page_title = $this_record['title'].' - playlists';
    break;

    case 'editSong':
        if (array_key_exists('update',$_POST)) {
            switch ($_POST['update']) {
            case "addNewSong":
                //a new song has been added - submit to database before displaying
                $value_array = acradb_convert_POST_data_into_recordValueArray($_POST, SBK_TABLE_NAME, SBK_DATABASE_NAME);
                $updatequery = acradb_generate_insert_query_from_value_array($value_array, SBK_TABLE_NAME);

                $result = acradb_get_query_result($updatequery, SBK_DATABASE_NAME);
                $id = mysql_insert_id();
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

        if(array_key_exists('playlist', $_GET)) {
            $playlist = $_GET['playlist'];
        } else {
            $playlist = false;
        }

        if($id) {
            $display = $display.'<ul class="menu local">';
            $display = $display.'<li><a href="#" onclick="jQuery(\'#edit-song-form input#display-id\').val('.($id-1).'); jQuery(\'#edit-song-form\').attr(\'action\',\'?action=editSong\').submit();">Edit Previous</a></li>';
            $display = $display.'<li><a href="?action=displaySong&id='.$id.'">Cancel edit</a></li>';
            $display = $display.'<li><a href="#" onclick="jQuery(\'#edit-song-form input#display-id\').val('.($id+1).'); jQuery(\'#edit-song-form\').attr(\'action\',\'?action=editSong\').submit();">Edit Next</a></li>';
            $display = $display.'</ul>';
        }
        $display = $display.'<span class="edit">';
        $display = $display.sbk_song_edit_form ($id, $playlist, true);
        $display = $display."</span>";
    break;
}

$display = acradisp_standardHTMLheader($page_title, array('index.css'), $STANDARD_JAVASCRIPTS).$display.'</body></html>';

echo $display;



?>
