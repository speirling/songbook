<?php
require_once 'admin/configure.inc.php';
require("wordHTML.php");
error_log("Run songbook index-----------------");
define('PLAYLIST_DIRECTORY', '/fileserver/data/www/songbook/playlists');
$songBookContent = "";

if(array_key_exists('action', $_GET)) {
	$action = $_GET['action'];
} else {
    $action = 'list';
}

$databasename = 'music_admin';
$tablename = 'lyrics';
$keyfieldname = 'id';

$display = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="en" xml:lang="en" xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>Songlists['.$action.']</title>
    <link rel="stylesheet" type="text/css" href="index.css" />
</head>
<body>';

switch ($action) {
    case 'list':
    default:
    $display = $display.'<h1>List of playlists:</h1>';
        $directoryList = scandir(PLAYLIST_DIRECTORY);
        foreach($directoryList as $filename) {
            if(!is_dir($filename)) {
                $path_parts = pathinfo($filename);
                if($path_parts['extension'] == 'playlist') {
                    $display = $display.'<a href="?action=displayPlaylist&playlist='.$path_parts['filename'].'">';
		            $display = $display.$path_parts['filename'];
		            $display = $display.'</a>';
                }
            }
        }
    break;

    case 'displayPlaylist':
        $playlist = $_GET['playlist'];
        if (array_key_exists('update',$_POST)) {
            switch ($_POST['update']) {
            case "PlaylistAddListOfSongs":
                //p($_POST);
                //couldn't get checkboxes as an array
                $song_id_array = array();
                foreach ($_POST as $key => $value) {
                    if(strstr($key, "song_")) {
                        $song_id_array[] = $value;
                    }
                }
                sbk_add_songs_to_playlist($song_id_array, $playlist) ;
            break;
            }
        } 
        $display = $display.'<h1>Playlist: ['.$playlist.']</h1>';
        
        $display = $display.'<ul class=menu>';
        $display = $display.'<li><a href="?action=editPlaylist&playlist='.$playlist.'">Edit this playlist</a></li> ';
        $display = $display.'<li><a href="?action=editSong&playlist='.$playlist.'">Add new song</a></li> ';
        $display = $display.'<li><a href="?action=addSongToPlaylist&playlist='.$playlist.'">Add existing song</a></li> ';
        $display = $display.'<li><a href="?action=list">List all playlists</a></li> ';
        $display = $display.'</ul>';

        $playlistContent = simplexml_load_file(PLAYLIST_DIRECTORY.'/'.$playlist.'.playlist');
        foreach($playlistContent->song as $thisSong){
            $this_record = acradb_get_single_record('music_admin', 'lyrics', 'id', $thisSong['id']);
            $display = $display.'<a href="?action=displaySong&id='.$thisSong['id'].'">';
            $display = $display.$this_record['title'];
            $display = $display.' ('.$this_record['written_by'].' '.$this_record['performed_by'].')';
            $display = $display.'</a><br />';
        }
        
    break;

    case 'displaySong':
        if (array_key_exists('update',$_POST)) {
            switch ($_POST['update']) {
            case "addNewSong":
                //a new song has been added - submit to database before displaying
                $value_array = acradb_convert_POST_data_into_recordValueArray($_POST, $tablename, $databasename);
                //p($value_array);
                $updatequery = acradb_generate_insert_query_from_value_array($value_array, $tablename);

                $result = acradb_get_query_result($updatequery, $databasename);
                $id = mysql_insert_id();
                //p($id, "added",$result, mysql_error(), $updatequery,$value_array);
                if(array_key_exists('playlist', $_POST)) {
                //p($id, $_POST['playlist']);
                    sbk_add_songs_to_playlist(array($id), $_POST['playlist']);
                } else {
                //p("no playlist specified");
                }
            break;
            }
        } else {
            $id = $_GET['id'];
        }
        $this_record = acradb_get_single_record($databasename, $tablename, $keyfieldname, $id);

        $display = $display.'<ul class=menu>';
        $display = $display.'<li><a href="?action=displaySong&id='.($id - 1).'">&laquo; Previous</a></li>';
        $display = $display.'<li><a href="?action=editSong&id='.$id.'">Edit this song</a></li>';
        $display = $display.'<li><a href="?action=editSong">Add a new song</a></li>';
        $display = $display.'<li><a href="?action=list">List playlists</a></li>';
        $display = $display.'<li><a href="?action=displaySong&id='.($id + 1).'">Next &raquo;</a></li>';
        $display = $display.'</ul>';

        $display = $display.'<div class="title">'.$this_record['title'].'</div>';
        $display = $display.'<div class="performed_by"><span class="label">performed by: </span><span class="data">'.$this_record['performed_by'].'</div></div>';
        $display = $display.'<div class="written_by"><span class="label">written by :</span><span class="data">'.$this_record['written_by'].'</div>';
        $display = $display.'<div class="content"><span class=data>'.nl2br($this_record['content']).'</div>';
        //$display = $display.'<div class="meta-tags">'.$this_record['meta_tags'].'</div>';
        //$display = $display.'<div class="original-filename">'.$this_record['original_filename'].'</div>';
    break;

    case 'editSong':
        $display = $display.'<form action = ?action=displaySong&id='.$id.' method="post">';
        if(array_key_exists('id', $_GET)) {
            $display = $display.'<input type="hidden" name="update" id="update" value="editExistingSong"></input>';
            $id = $_GET['id'];
            $this_record = acradb_get_single_record('music_admin', 'lyrics', 'id', $id);
  
            $display = $display.'<h1>Edit song</h1>';
            $display = $display.'<ul class=menu>';
            $display = $display.'<li><a href="?action=editSong&id='.($id - 1).'">Edit Previous</a></li>';
            $display = $display.'<li><a href="?action=displaySong&id='.$id.'">Cancel edit</a></li>';
            $display = $display.'<li><a href="?action=editSong">Add a new song</a></li>';
            $display = $display.'<li><a href="?action=list">List playlists</a></li>';
            $display = $display.'<li><a href="?action=editSong&id='.($id + 1).'">Edit Next</a></li>';
            $display = $display.'</ul>';
            $display = $display.'<input type="hidden" name="id" id="id" value="'.$id.'"></input>';
        } else {
            $display = $display.'<input type="hidden" name="update" id="update" value="addNewSong"></input>';
            $display = $display.'<h1>Add a new song</h1>';
            if(array_key_exists('playlist', $_GET)) {
                $display = $display.'<h2>to playlist [' . $_GET['playlist'] . ']</h2>';
            }
            $display = $display.'<ul class=menu>';
            $display = $display.'<li><a href="?action=list">List playlists</a></li>';
            $display = $display.'</ul>';
            $this_record = array(
                'title' => '',
                'performed_by' => '',
                'written_by' => '',
                'content' => '',
                'meta_tags' => '',
                'original_filename' => ''
            );
        }
        if(array_key_exists('playlist', $_GET)) {
            $display = $display.'<input type="hidden" name="playlist" id="update" value="'.$_GET['playlist'] .'"></input>';
        }
        $display = $display.'<div class="title">title: <input type="text" name="title" id="title" size=80 value="'.$this_record['title'].'"></input></div>';
        $display = $display.'<div class="performed_by"><span class=label>performed by: </span><input type="text" name="performed_by" id="performed_by" size=80 value="'.$this_record['performed_by'].'"></input></div>';
        $display = $display.'<div class="written_by"><span class=label>written by: </span><input type="text" name="written_by" id="written_by" size=80 value="'.$this_record['written_by'].'"></input></div>';
        $display = $display.'<div class="content">content: <br /><textarea name="content" id="content" cols=80 rows=20>'.$this_record['content'].'</textarea></div>';
        $display = $display.'<div class="meta_tags"><input type="text" name="meta_tags" id="meta_tags" size=80 value="'.$this_record['meta_tags'].'"></input></div>';
        $display = $display.'<div class="original_filename"><input type="text" name="original_filename" id="original_filename" size=80 value="'.$this_record['original_filename'].'"></input></div>';
        $display = $display.'<input type=submit value="Save changes" />';
        $display = $display.'</form>';
    break;

    case 'addPlaylist':
        p('add a new playlist');
    break;

    case 'editPlaylist':
        p('edit an existing playlist');
    break;

    case 'addSongToPlaylist':
        $result = acradb_get_query_result("select * from ".$tablename, $databasename);
        $display = $display.'<form action = ?action=displayPlaylist&playlist='.$_GET['playlist'].' method="post">';
        $display = $display.'<input type="hidden" name="update" id="update" value="PlaylistAddListOfSongs"></input>';
        $display = $display.'<h1>All available songs</h1>';
        $display = $display.'<input type=submit value="Save changes" />';
        while ($this_record = mysql_fetch_assoc($result)) {
            $display = $display.'<span class="song">';
            $display = $display.'<input type="checkbox" name="song_'.$this_record['id'].'" value="'.$this_record['id'].'" />';
            $display = $display.'<span class="title">'.$this_record['title'].'</span>';
            $display = $display.'<span class="detail"> (<span class="written_by">'.$this_record['written_by'].'</span> <span class="performed_by">'.$this_record['performed_by'].'</span>)</span>';
            $display = $display.'</span>';
    	}
    	$display = $display.'<input type=submit value="Save changes" />';
    	$display = $display.'</form>';
    break;

    case 'outputWord':
        foreach($songList as $filename) {
            if(!is_dir($filename)) {
           $sectionNumber = $sectionNumber + 1;
             $lyrics_as_string = "<div class=Section".$sectionNumber.">";
            $lineCount = 0;
              $lyrics_as_array = file($dir."/".$filename);
                foreach($lyrics_as_array as $line) {
                    $lineCount = $lineCount + 1;
                    if($lineCount == 1) {
                        $lyrics_as_string .= "<h1>".$line."</h1>\n";
                    } elseif($lineCount == 2) {
                        $lyrics_as_string .= "<h2>".$line."</h2>\n<p class='lyrics'>";
                    } else {
                        $lyrics_as_string .= $line."<br />\n";
                    }
                }
                $songBookContent .= $lyrics_as_string."</p>\n";
                $songBookContent .= "\n</div>".$pageBreak."\n";
                $PageDefinitions = $PageDefinitions."\n@page Section".$sectionNumber."\n{";
                $PageDefinitions = $PageDefinitions.$pageDefinitionSingleColumn;
                if($lineCount > 50) {
                    $PageDefinitions = $PageDefinitions.$pageDefinitionDoubleColumn;
                }
                $PageDefinitions = $PageDefinitions."}\n";
                $PageDefinitions = $PageDefinitions."div.Section".$sectionNumber."\n{page:Section".$sectionNumber.";}\n";
            }
        }

        $songBook = $fileHeaderBeforeTitle.
                        $title.
                        $fileHeaderFromTitleToPageDefinitions.
                        $PageDefinitions.
                        $fileHeaderAfterPageDefinitions.
                        $titlePage.
                        $contentsPage.
                        $songBookContent.
                        $footer;

        $fp = fopen($title.'.htm', 'w');
        if(fwrite($fp, $songBook)) {
            echo "File ".$title.".htm written";
        } else {
            echo "ERROR : There was a problem writing file".$title.".htm";
        }
        fclose($fp);
    break;
}

$display = $display.'
</body>
</html>';
echo $display;



function sbk_add_songs_to_playlist($song_id_array, $playlist) {
    //p('add an existing song to a playlist', $song_id, $playlist);
    
    //read playlist
    $playlistContent = simplexml_load_file(PLAYLIST_DIRECTORY.'/'.$playlist.'.playlist');
    foreach($song_id_array as $id) {
        $new_song = $playlistContent->addChild('song')->addAttribute('id', $id);
    }
    //p($playlistContent);
    $playlistContent->saveXML(PLAYLIST_DIRECTORY.'/'.$playlist.'.playlist');
}
?>
