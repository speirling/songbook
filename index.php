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
$menu = $menu.'<div class="menu">';
$menu = $menu.'<div class="menu main">';
$menu = $menu.'<span class="menu-list-of-playlists"></span>';
$menu = $menu.'<span class="buttons">';
$menu = $menu.'<a href="?action=index">index of all songs</a>';
$menu = $menu.'<a href="?action=displayPlaylist">Add a new playlist</a> ';
$menu = $menu.'<a href="?action=editSong">Add new song</a> ';
$menu = $menu.'</span>';
$menu = $menu.'</div>';

$css = array();
define(CSS_PATH, 'css/');
$css[] = CSS_PATH.'common.css';

switch ($action) {
    case 'listAllSongs':
        $css[] = CSS_PATH.CSS_PATH.'index.css';
        $css[] = CSS_PATH.'menu.css';
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
        $css[] = CSS_PATH.'index.css';
        $css[] = CSS_PATH.'menu.css';
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
        $css[] = CSS_PATH.'index.css';
        $css[] = CSS_PATH.'menu.css';
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
        $css[] = CSS_PATH.'index.css';
        $css[] = CSS_PATH.'menu.css';
        $playlist = $_GET['playlist'];
        $page_title = "[".$playlist."]";

        $menu = $menu.'<select class="menu local">';
        $menu = $menu.'<option value="?action=pdfPlaylist&playlist='.$playlist.'">table</option>';
        $menu = $menu.'<option value="?action=pdfPlaylist&playlist='.$playlist.'&pdf">table pdf</option>';
        $menu = $menu.'<option value="?action=emailPlaylist&playlist='.$playlist.'&pdf">email</option>';
        $menu = $menu.'<option value="?action=index&playlist='.$playlist.'">Show an index of the songs in this playlist</option>';
        $menu = $menu.'<option value="?action=playlistBook&playlist='.$playlist.'" target="new">Playlist as a book</option>';
        $menu = $menu.'<option value="?action=playlistBook&playlist='.$playlist.'&pdf">Playlist as a book pdf</option>';
        $menu = $menu.'</select>';
        $menu = $menu."
        <script type=\"text/javascript\">
            $(document).ready(function() {
        		jQuery('.menu.local').change(function () {
        			location.href = jQuery(this).val();
        		});
            });
        </script>";

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
        $css[] = CSS_PATH.'index.css';
        $css[] = CSS_PATH.'song-lyrics.css';
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
        $css[] = CSS_PATH.'index.css';
        $playlist = $_GET['playlist'];
        $playlistContent = simplexml_load_file(PLAYLIST_DIRECTORY.'/'.$playlist.'.playlist');
        $display = sbk_convert_playlistXML_to_table($playlistContent);
        if($_GET['pdf']) {
            sbk_output_pdf($display, $playlistContent['title'], 'landscape');
        }
    break;

    case 'emailPlaylist':
        $css[] = CSS_PATH.'index.css';
        $playlist = $_GET['playlist'];
        $playlistContent = simplexml_load_file(PLAYLIST_DIRECTORY.'/'.$playlist.'.playlist');
        $display = sbk_convert_playlistXML_to_orderedlist($playlistContent, $show_key = TRUE, $show_capo = TRUE, $show_singer = TRUE, $show_id = FALSE, $show_writtenby = FALSE, $show_performedby = FALSE);
    break;

    case 'playlistBook':
        //$css[] = CSS_PATH.'index.css';
        $css[] = CSS_PATH.'playlist-book.css';
        $css[] = CSS_PATH.'song-lyrics.css';
        $css[] = CSS_PATH.'song-index.css';
        $playlist = $_GET['playlist'];
        $playlistContent = simplexml_load_file(PLAYLIST_DIRECTORY.'/'.$playlist.'.playlist');
        $ID_array = sbk_getIDarray($playlistContent);
        $display = '';

        $display = $display.'<div class="playlist-page"></div>';
        $display = $display.sbk_generate_index($ID_array);
        sort($ID_array);
        $display = $display.sbk_print_multiple_songs($ID_array);
        if(array_key_exists('pdf', $_GET)) {
          $display = acradisp_standardHTMLheader($page_title, $css, $STANDARD_JAVASCRIPTS).$display.'</body></html>';
          sbk_output_pdf($display, 'SongBook - '.$playlistContent['title']);
        }

        $display = $display."
        <script type=\"text/javascript\">
            $(document).ready(function() {
            	var playlist = new SBK.PlayListPrint('".$playlist."', jQuery('.playlist-page'));
            	playlist.render();
            });
        </script>";
    break;

    case 'displaySong':
        //$css[] = CSS_PATH.'index.css';
        $css[] = CSS_PATH.'menu.css';
        $css[] = CSS_PATH.'song-lyrics.css';
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
        $menu = $menu.'<ul class="menu local">';
        $menu = $menu.'<li><a href="?action=displaySong&id='.($id - 1).'">&laquo; Previous</a></li>';
        $menu = $menu.'<li><a href="?action=editSong&id='.$id.'">Edit this song</a></li>';
        $menu = $menu.'<li><a href="?action=pdfSong&id='.$id;
        if(!is_null($key)) {
            $display = $display.'&key='.urlencode($key);
        }
        if(!is_null($singer)) {
            $display = $display.'&singer='.$singer;
        }
        if(!is_null($capo)) {
            $display = $display.'&capo='.$capo;
        }
        $menu = $menu.'">.pdf</a></li>';
        $menu = $menu.'<li><a href="?action=displaySong&id='.($id + 1).'">Next &raquo;</a></li>';
        $menu = $menu.'</ul>';

        $this_record = sbk_get_song_record($id);
        $display = $display.sbk_song_html($this_record, $key, $singer, $capo);
        $page_title = $this_record['title'].' - playlists';
    break;

    case 'editSong':
        $css[] = CSS_PATH.'index.css';
        $css[] = CSS_PATH.'menu.css';
        $css[] = CSS_PATH.'song-edit.css';
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
            $menu = $menu.'<ul class="menu local">';
            $menu = $menu.'<li><a href="#" onclick="jQuery(\'#edit-song-form input#display-id\').val('.($id-1).'); jQuery(\'#edit-song-form\').attr(\'action\',\'?action=editSong\').submit();">Edit Previous</a></li>';
            $menu = $menu.'<li><a href="?action=displaySong&id='.$id.'">Cancel edit</a></li>';
            $menu = $menu.'<li><a href="#" onclick="jQuery(\'#edit-song-form input#display-id\').val('.($id+1).'); jQuery(\'#edit-song-form\').attr(\'action\',\'?action=editSong\').submit();">Edit Next</a></li>';
            $menu = $menu.'</ul>';
        }
        $display = $display.'<span class="edit">';
        $display = $display.sbk_song_edit_form ($id, $playlist, true);
        $display = $display."</span>";
    break;
}

$menu = $menu.'</div>';
$menu = $menu."
<script type=\"text/javascript\">
    $(document).ready(function() {
		new SBK.PlaylistSelector(jQuery('.menu-list-of-playlists')).render(
			function (list) {
				list.change(function () {
        			location.href = \"?action=displayPlaylist&playlist=\" + jQuery(this).val();
        		});
			}
		);
    });
</script>";

$display = $menu.$display;
$display = acradisp_standardHTMLheader($page_title, $css, $STANDARD_JAVASCRIPTS).$display.'</body></html>';

echo $display;



?>
