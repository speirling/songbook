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

function sbk_list_all_songs_in_database() {
        $result = acradb_get_query_result("select * from ".SBK_TABLE_NAME, SBK_DATABASE_NAME);
        $outputHTML = '';
        $outputHTML = $outputHTML.'<div id="allsongs" class="playlist">';
        //$outputHTML = $outputHTML.'<h1>All songs</h1>';
        $outputHTML = $outputHTML.'<ul>';
        while ($this_record = mysql_fetch_assoc($result)) {
            $outputHTML = $outputHTML.'<li class="song" id="'.$this_record['id'].'">';
            $outputHTML = $outputHTML.'<span class="title">'.$this_record['title'].'</span>';
            $outputHTML = $outputHTML.'<span class="detail"> (<span class="written_by">'.$this_record['written_by'].'</span> <span class="performed_by">'.$this_record['performed_by'].'</span>)</span>';
            $outputHTML = $outputHTML.'</li>';
    	}
        $outputHTML = $outputHTML.'</ul>';
    	$outputHTML = $outputHTML.'</div>';
    	return $outputHTML;
}

?>