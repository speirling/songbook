<?php



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
    $outputHTML = '';
    $outputHTML = $outputHTML.'<div id="playlist-holder">';
    $outputHTML = $outputHTML.'<textarea class="playlist-title">'.$playlistContent['title'].'</textarea>';
    $outputHTML = $outputHTML.'<ul title='.$playlistContent['title'].'>';
    foreach ($playlistContent->set as $thisSet) {
        $outputHTML = $outputHTML.'<li class="set playlist">';
        $outputHTML = $outputHTML.'<textarea class="set-title">'.$thisSet['label'].'</textarea>';
        $outputHTML = $outputHTML.'<ul>';
        $outputHTML = $outputHTML.'<li class="dummy">&nbsp;</li>';
        foreach($thisSet->song as $thisSong) {
            $this_record = acradb_get_single_record('music_admin', 'lyrics', 'id', $thisSong['id']);
            $outputHTML = $outputHTML.'<li class="song" id="'.$this_record['id'].'">';
            $outputHTML = $outputHTML.'<input type="text" class="key" value="'.$thisSong['key'].'"></input>';
            $outputHTML = $outputHTML.'<input type="text" class="singer" value="'.$thisSong['singer'].'"></input>';
            $outputHTML = $outputHTML.'<span class="title">'.$this_record['title'].'</span>';
            $outputHTML = $outputHTML.'<span class="detail"> (<span class="written_by">'.$this_record['written_by'].'</span> <span class="performed_by">'.$this_record['performed_by'].'</span>)</span>';
            $outputHTML = $outputHTML.'</li>';
        }
        $outputHTML = $outputHTML.'</ul>';
    }
    $outputHTML = $outputHTML.'</li>';
    $outputHTML = $outputHTML.'</ul></div>';
    return $outputHTML;
}

function sbk_convert_list_to_playlistXML($list) {
    p($list);
    $list = str_replace('\&quot;', '', $list);
    $list = str_replace('\"', '"', $list);
    $list = preg_replace('/&nbsp;/', '&#160;', $list); //&nbsp; doesn't work in XML unless it's specifically declared.
    $list_object = simplexml_load_string('<container>'.$list.'</container>');
    $playlistContent = new SimpleXMLElement('<?xml version="1.0" standalone="yes"?><songlist></songlist>');
    $playlistContent->addAttribute('title', $list_object->textarea);
    foreach($list_object->ul->li as $thisSet) {
        $XMLset = $playlistContent->addChild('set');
        $XMLset->addAttribute('label', $thisSet->textarea);
        foreach($thisSet->ul[0]->li as $thisSong) {
            $this_id = (string) $thisSong['id'];
            if(is_numeric($this_id)) {
                $XMLsong = $XMLset->addChild('song','');
                $XMLsong->addAttribute('id', $this_id);
                $XMLsong->addAttribute('key',(string) $thisSong['key']);
                $XMLsong->addAttribute('singer',(string) $thisSong['singer']);
            }
        }
    }

    return $playlistContent;
}

function sbk_list_all_songs_in_database($search_string = false) {
        $searchWHERE = '';
        if($search_string) {
            $searchWHERE = $searchWHERE.' WHERE ';
            $searchWHERE = $searchWHERE.' `title` LIKE \'%'.$search_string.'%\' OR';
            $searchWHERE = $searchWHERE.' `written_by` LIKE \'%'.$search_string.'%\' OR';
            $searchWHERE = $searchWHERE.' `performed_by` LIKE \'%'.$search_string.'%\' OR';
            $searchWHERE = $searchWHERE.' `content` LIKE \'%'.$search_string.'%\' OR';
            $searchWHERE = $searchWHERE.' `meta_tags` LIKE \'%'.$search_string.'%\' ';
        }
        $result = acradb_get_query_result("select * from ".SBK_TABLE_NAME.$searchWHERE, SBK_DATABASE_NAME);
        $outputHTML = '';
        $outputHTML = $outputHTML.'<div id="allsongs">';
        $outputHTML = $outputHTML.'<span class="numberofrecords">'.mysql_num_rows($result).'</span>';
        $outputHTML = $outputHTML.'<ul>';
        while ($this_record = mysql_fetch_assoc($result)) {
            $outputHTML = $outputHTML.'<li class="song" id="'.$this_record['id'].'">';
            $outputHTML = $outputHTML.'<input class="key" type="text" value="">';
            $outputHTML = $outputHTML.'<input class="singer" type="text" value="">';
            $outputHTML = $outputHTML.'<span class="title">'.$this_record['title'].'</span>';
            $outputHTML = $outputHTML.'<span class="detail"> (<span class="written_by">'.$this_record['written_by'].'</span> <span class="performed_by">'.$this_record['performed_by'].'</span>)</span>';
            $outputHTML = $outputHTML.'</li>';
    	}
        $outputHTML = $outputHTML.'</ul>';
    	$outputHTML = $outputHTML.'</div>';
    	return $outputHTML;
}

function sbk_getIDarray($playlistXML = false) {
    $ID_array = Array();
    if($playlistXML) {
        foreach ($playlistXML->set as $this_set) {
            foreach($this_set->song as $this_song) {
                if(!in_array($this_song['id'], $ID_array)) {
                    $ID_array[] = (string) $this_song['id'];
                }
            }
        }
    } else { //no playlist passed - get all IDs in the database
        $result = acradb_get_query_result("select id from ".SBK_TABLE_NAME, SBK_DATABASE_NAME);
        while ($this_record = mysql_fetch_assoc($result)) {
            $ID_array[] = $this_record['id'];
        }
    }
    return $ID_array;
}

function sbk_generate_index($ID_array) {
    $html = '';
    $index = Array(
        'title' => Array(),
        'writtenby' => Array(),
        'performedby' => Array(),
        'categories' => Array()
    );
    $id_csv = "(";

    foreach ($ID_array as $this_id) {
        $id_csv = $id_csv.$this_id.", ";
    }
    $id_csv = substr($id_csv, 0, -2);
    $id_csv = $id_csv.")";

    $result = acradb_get_query_result("select * from ".SBK_TABLE_NAME." WHERE id IN ".$id_csv, SBK_DATABASE_NAME);
    while ($this_record = mysql_fetch_assoc($result)) {
        $this_title = trim($this_record['title']);
        $this_id = $this_record['id'];
        $index['title'][$this_title] = $this_id;
        $parts = explode(",",$this_record['written_by']);
        foreach($parts as $this_writtenby) {
            $this_writtenby = trim($this_writtenby);
            if($this_writtenby !== '') {
                $index['writtenby'][trim($this_writtenby)][$this_title] = $this_id;
            }
        }
        $parts = explode(",",$this_record['performed_by']);
        foreach($parts as $this_performedby) {
            $this_performedby = trim($this_performedby);
            if($this_performedby !== '') {
                $index['performedby'][trim($this_performedby)][$this_title] = $this_id;
            }
        }
        $parts = explode(",",$this_record['meta_tags']);
        foreach($parts as $this_category) {
            $this_category = trim($this_category);
            if($this_category !== '') {
                $index['categories'][trim($this_category)][$this_title] = $this_id;
            }
        }
    }
    $html = $html.'<h1>Sorted by title</h1>';
    $html = $html.'<div class="indent">';
    ksort($index['title']);
    foreach($index['title'] as $this_title => $this_id) {
        $html = $html.'<div class="song" id="'.$this_id.'"><span class="title">'.$this_title.'</span><span class="id">'.$this_id.'</span></div>';
    }
    $html = $html.'</div>';

    $html = $html.'<h1>Sorted by composer</h1>';
    $html = $html.'<div class="indent">';
    ksort($index['writtenby']);
    foreach($index['writtenby'] as $this_composer => $this_songarray) {
        $html = $html.'<h2>'.$this_composer.'</h2>';
        ksort($this_songarray);
        foreach($this_songarray as $this_title => $this_id) {
            $html = $html.'<div class="song" id="'.$this_id.'"><span class="title">'.$this_title.'</span><span class="id">'.$this_id.'</span></div>';
        }
    }
    $html = $html.'</div>';

    $html = $html.'<h1>Sorted by performer</h1>';
    $html = $html.'<div class="indent">';
    ksort($index['performedby']);
    foreach($index['performedby'] as $this_performer => $this_songarray) {
        $html = $html.'<h2>'.$this_performer.'</h2>';
        ksort($this_songarray);
        foreach($this_songarray as $this_title => $this_id) {
            $html = $html.'<div class="song" id="'.$this_id.'"><span class="title">'.$this_title.'</span><span class="id">'.$this_id.'</span></div>';
        }
    }
    $html = $html.'</div>';

    $html = $html.'<h1>Sorted by category</h1>';
    $html = $html.'<div class="indent">';
    ksort($index['categories']);
    foreach($index['categories'] as $this_category => $this_songarray) {
        $html = $html.'<h2>'.$this_category.'</h2>';
        $html = $html.'<div class="indent">';
        ksort($this_songarray);
        foreach($this_songarray as $this_title => $this_id) {
            $html = $html.'<div class="song" id="'.$this_id.'"><span class="title">'.$this_title.'</span><span class="id">'.$this_id.'</span></div>';
        }
        $html = $html.'</div>';

    }
    $html = $html.'</div>';



    return $html;
}
?>