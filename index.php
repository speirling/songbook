<?php
require_once 'admin/configure.inc.php';
require("wordHTML.php");
error_log("Run songbook index-----------------");
define('PLAYLIST_DIRECTORY', 'playlists');
$songBookContent = "";

if(array_key_exists('action', $_GET)) {
	$action = $_GET['action'];
} else {
    $action = 'list';
}

define("SBK_DATABASE_NAME", 'music_admin');
define("SBK_TABLE_NAME", 'lyrics');
define("SBK_KEYFIELD_NAME", 'id');

$STANDARD_JAVASCRIPTS[] = '../acra_i/js/jquery.js';
$STANDARD_JAVASCRIPTS[] = '../acra_i/js/jstree/jquery.jstree.js';

$localJavascriptStatements = "
$(document).ready(function() {
	jQuery('.songlist').jstree().bind('loaded.jstree', function (event, data) {
        jQuery(this).jstree('open_all');
    });
    jQuery('.songlist a').live('click', function(e) {
    	location.href = jQuery(this).attr('href');
    });

    jQuery('.allsongs').jstree({
	        'dnd' : {
	            'drop_finish' : function () {
	                alert('DROP');
	            },
	            'drag_check' : function (data) {
	                if(data.r.attr('id') == 'phtml_1') {
	                    return false;
	                }
	                return {
	                    after : false,
	                    before : false,
	                    inside : true
	                };
	            },
	            'drag_finish' : function (data) {
	                alert('DRAG OK');
	            }
	        },
	        'plugins' : [ 'themes', 'html_data', 'dnd' ]
	    });
});
";
$display = acradisp_standardHTMLheader('Songlists['.$action.']', array('index.css'), $STANDARD_JAVASCRIPTS, $localJavascriptStatements);

switch ($action) {
    case 'list':
    default:
        $display = $display.'<h1>List of playlists:</h1>';

        $display = $display.'<ul class=menu>';
        $display = $display.'<li><a href="?action=addNewPlaylist">Add a new playlist</a></li> ';
        $display = $display.'<li><a href="?action=editSong">Add new song</a></li> ';
        $display = $display.'</ul>';

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
        $display = $display.'<table><tr><td>';
        $display = $display.sbk_convert_playlistXML_to_list($playlistContent);
        $display = $display.'</td><td>';
        $display = $display.sbk_list_all_songs_in_database();
        $display = $display.'</td></tr></table>';

    break;

    case 'displaySong':
        $number_of_lyric_lines_per_page = 60;
        if (array_key_exists('update',$_POST)) {
            switch ($_POST['update']) {
            case "addNewSong":
                //a new song has been added - submit to database before displaying
                $value_array = acradb_convert_POST_data_into_recordValueArray($_POST, SBK_TABLE_NAME, SBK_DATABASE_NAME);
                //p($value_array);
                $updatequery = acradb_generate_insert_query_from_value_array($value_array, SBK_TABLE_NAME);

                $result = acradb_get_query_result($updatequery, SBK_DATABASE_NAME);
                $id = mysql_insert_id();
                //p($id, "added",$result, mysql_error(), $updatequery,$value_array);
                if(array_key_exists('playlist', $_POST)) {
                //p($id, $_POST['playlist']);
                    sbk_add_songs_to_playlist(array($id), $_POST['playlist']);
                } else {
                //p("no playlist specified");
                }
            break;

            case "editExistingSong":
                $id = $_POST['id'];
                $value_array = acradb_convert_POST_data_into_recordValueArray($_POST, SBK_TABLE_NAME, SBK_DATABASE_NAME);
                $updatequery = acradb_generate_update_query_from_value_array($value_array, SBK_TABLE_NAME, 'id');
                $result = acradb_get_query_result($updatequery, SBK_DATABASE_NAME);
            break;
            }
        } else {
            $id = $_GET['id'];
        }
        $this_record = acradb_get_single_record(SBK_DATABASE_NAME, SBK_TABLE_NAME, SBK_KEYFIELD_NAME, $id);

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
        $content = $this_record['content'];
        $content = preg_replace('/\n/','</div><div class="line">', $content);
        $content = preg_replace('/<div class=\"line\">[\s]*?<\/div>/', '<div class="line">&nbsp;</div>', $content);
        $content = preg_replace('/\[(.*?)\]/','<span class="chord">$1</span>', $content);
        $content = preg_replace('/&nbsp;/', '&#160;', $content); //&nbsp; doesn't work in XML unless it's specifically declared.
        $contentHTML = '<div class="content"><div class="line">'.$content.'</div></div>';
        $contentXML = new SimpleXMLElement($contentHTML);
        $line_count = 0;
        $formattedContentXML = new SimpleXMLElement('<div class="content"></div>');
        $table = $formattedContentXML->addChild('table');
        $table_row = $table->addChild('tr');
        $current_column = $table_row->addChild('td');
        foreach($contentXML->xpath('//div[@class="line"]') as $this_line) {
            $line_count = $line_count + 1;
            if(($line_count % $number_of_lyric_lines_per_page) === 0) {
                $current_column = $table_row->addChild('td');
            }
            $new_line = $current_column->addChild('div', $this_line);
            $new_line->addAttribute('class', 'line');
        }
        $display = $display.str_replace('<?xml version="1.0"?>','',$formattedContentXML->asXML());
        //$display = $display.'<div class="meta-tags">'.$this_record['meta_tags'].'</div>';
        //$display = $display.'<div class="original-filename">'.$this_record['original_filename'].'</div>';
    break;

    case 'editSong':
        $display = $display.'<form action = "?action=displaySong" method="post">';
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

    case 'addNewPlaylist':
        p('add a new playlist');
    break;

    case 'editPlaylist':
        p('edit an existing playlist');
    break;

    case 'addSongToPlaylist':

        $playlist = $_GET['playlist'];
        $playlistContent = simplexml_load_file(PLAYLIST_DIRECTORY.'/'.$playlist.'.playlist');
        $display = $display.'<form action = ?action=displayPlaylist&playlist='.$playlist.' method="post">';
        $display = $display.'<input type="hidden" name="update" id="update" value="PlaylistAddListOfSongs"></input>';
        $display = $display.'<h1>All available songs</h1>';

        $display = $display.'<ul class=menu>';
        $display = $display.'<li><a href="?action=displayPlaylist&playlist='.$playlist.'">View this playlist (cancel)</a></li> ';
        $display = $display.'<li><a href="?action=list">List all playlists (home)</a></li> ';
        $display = $display.'</ul>';

        $display = $display.'<input type=submit value="Save changes" />';

        $result = acradb_get_query_result("select * from ".SBK_TABLE_NAME, SBK_DATABASE_NAME);
        while ($this_record = mysql_fetch_assoc($result)) {
            $display = $display.'<span class="song">';
            foreach ($playlistContent->set as $thisSet) {
                $display = $display.'['.$thisSet['label'].': <input type="checkbox" name="set_'.$thisSet['id'].'_song_'.$this_record['id'].'" value="'.$this_record['id'].'" />] ';
            }
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



function sbk_add_songs_to_playlist($song_id_array, $sets, $playlist) {
    p('add an existing song to a playlist', $song_id, $sets, $playlist);

    //read playlist
    $playlistContent = simplexml_load_file(PLAYLIST_DIRECTORY.'/'.$playlist.'.playlist');
    foreach($sets as $set) {
        $thisSet =  $playlistContent->xpath('//set[@id='.$set.']');
        foreach($song_id_array[$set] as $id) {
            $new_song = $thisSet->addChild('song')->addAttribute('id', $id);
        }
    }
    //p($playlistContent);
    $playlistContent->saveXML(PLAYLIST_DIRECTORY.'/'.$playlist.'.playlist');
}

function sbk_convert_playlistXML_to_list($playlistContent) {
    $playlist_display = '';
    $playlist_display = $playlist_display.'<div class="songlist">';
    //$playlist_display = $playlist_display.'<h1>'.$playlistContent['title'].'</h1>';
    $playlist_display = $playlist_display.'<ul>';
    foreach ($playlistContent->set as $thisSet) {
        $playlist_display = $playlist_display.'<li class=>';
        $playlist_display = $playlist_display.'<a href=" "><h2>'.$thisSet['label'].'</h2></a>';
        $playlist_display = $playlist_display.'<ul class="set">';
        foreach($thisSet->song as $thisSong){
            $this_record = acradb_get_single_record('music_admin', 'lyrics', 'id', $thisSong['id']);
            $playlist_display = $playlist_display.'<li id="'.$thisSong['id'].'"><a href="?action=displaySong&id='.$thisSong['id'].'">';
            $playlist_display = $playlist_display.$this_record['title'];
            $playlist_display = $playlist_display.' ('.$this_record['written_by'].' '.$this_record['performed_by'].')';
            $playlist_display = $playlist_display.'</a></li>';
        }
        $playlist_display = $playlist_display.'</ul>';
    }
    $playlist_display = $playlist_display.'</li>';
    $playlist_display = $playlist_display.'</ul></div>';
    return $playlist_display;
}

function sbk_list_all_songs_in_database() {
        $result = acradb_get_query_result("select * from ".SBK_TABLE_NAME, SBK_DATABASE_NAME);
        $outputHTML = '';
        $outputHTML = $outputHTML.'<div class="allsongs">';
        $outputHTML = $outputHTML.'<ul>';
        while ($this_record = mysql_fetch_assoc($result)) {
            $outputHTML = $outputHTML.'<li class="song">';
            $outputHTML = $outputHTML.'<span class="title">'.$this_record['title'].'</span>';
            $outputHTML = $outputHTML.'<span class="detail"> (<span class="written_by">'.$this_record['written_by'].'</span> <span class="performed_by">'.$this_record['performed_by'].'</span>)</span>';
            $outputHTML = $outputHTML.'</li>';
    	}
        $outputHTML = $outputHTML.'</ul>';
    	$outputHTML = $outputHTML.'</div>';
    	return $outputHTML;
}
?>
