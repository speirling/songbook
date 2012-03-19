<?php

$NOTE_VALUE_ARRAY = Array(
    'C'  => 0,
    'C#' => 1,
    'Db' => 1,
    'D'  => 2,
    'D#' => 3,
    'Eb' => 3,
    'E'  => 4,
    'E#' => 5,
    'Fb' => 4,
    'F'  => 5,
    'F#' => 6,
    'Gb' => 6,
    'G'  => 7,
    'G#' => 8,
    'Ab' => 8,
    'A'  => 9,
    'A#' => 10,
    'Bb' => 10,
    'B'  => 11,
    'B#' => 0,
    'Cb' => 11
);

$VALUE_NOTE_ARRAY_DEFAULT = Array(
    0 => 'C',
    1 => 'C#',
    2 => 'D',
    3 => 'Eb',
    4 => 'E',
    5 => 'F',
    6 => 'F#',
    7 => 'G',
    8 => 'G#',
    9 => 'A',
    10=> 'Bb',
    11=> 'B'
);

$VALUE_NOTE_ARRAY_SHARP = Array(
    0 => 'C',
    1 => 'C#',
    2 => 'D',
    3 => 'D#',
    4 => 'E',
    5 => 'F',
    6 => 'F#',
    7 => 'G',
    8 => 'G#',
    9 => 'A',
    10=> 'A#',
    11=> 'B'
);

$VALUE_NOTE_ARRAY_FLAT = Array(
    0 => 'C',
    1 => 'Db',
    2 => 'D',
    3 => 'Eb',
    4 => 'E',
    5 => 'F',
    6 => 'Gb',
    7 => 'G',
    8 => 'Ab',
    9 => 'A',
    10=> 'Bb',
    11=> 'B'
);

function sbk_convert_playlistXML_to_editable_list($playlistContent) {
    return sbk_convert_playlistXML_to_list($playlistContent, $editable = true, $show_key = TRUE, $show_capo = TRUE, $show_singer = TRUE, $show_id = false, $show_writtenby = false, $show_performedby = false, $show_duration = true, $show_introduction = true);
}

function sbk_convert_playlistXML_to_orderedlist($playlistContent, $show_key = TRUE, $show_capo = TRUE, $show_singer = TRUE, $show_id = TRUE, $show_writtenby = TRUE, $show_performedby = TRUE, $show_duration = true, $show_introduction = false) {
    return sbk_convert_playlistXML_to_list($playlistContent, $editable = false, $show_key, $show_capo, $show_singer, $show_id, $show_writtenby, $show_performedby, $show_duration, $show_introduction);
}

function sbk_convert_playlistXML_to_table($playlistContent) {
    $outputHTML = '';
    $number_of_columns = count($playlistContent->set);
    $column_width = floor(100/$number_of_columns);
    $outputHTML = $outputHTML.'<div id="playlist-table">';
    $outputHTML = $outputHTML.sbk_convert_playlistXML_to_list($playlistContent, $editable = false, $show_key = TRUE, $show_capo = TRUE, $show_singer = TRUE, $show_id = TRUE, $show_writtenby = TRUE, $show_performedby = TRUE, $show_duration = true);
    $outputHTML = $outputHTML.'</div>';
    return $outputHTML;
}
/**
 *
 * Converts a playlist XML object to text representing a HTML unordered list
 * @param simpleXML_object $playlistContent
 * @param boolean $editable
 * @param boolean $show_key
 * @param boolean $show_singer
 * @param boolean $show_id
 * @param boolean $show_writtenby
 * @param boolean $show_performedby
 * @param boolean $show_duration
 * @param boolean $show_introduction
 */
function sbk_convert_playlistXML_to_list($playlistContent, $editable = false, $show_key = TRUE, $show_capo = TRUE, $show_singer = TRUE, $show_id = TRUE, $show_writtenby = TRUE, $show_performedby = TRUE, $show_duration = true, $show_introduction = true) {
    //p($playlistContent->asXML(), $editable, $show_key, $show_singer, $show_id, $show_writtenby, $show_performedby, $show_duration, $show_introduction);

    $outputHTML = '';
    if($editable) {
        $textarea = 'textarea';
        $input_start = 'input type="text"';
        $input_middle = ' value="';
        $input_end = '" /';
    } else {
        $textarea = 'span';
        $input_start = 'span';
        $input_middle = '>';
        $input_end = '</span';
    }
    $outputHTML = $outputHTML.'<'.$input_start.' class="playlist-title"'.$input_middle.$playlistContent['title'].$input_end.'>';
    $outputHTML = $outputHTML.'<'.$input_start.' class="act"'.$input_middle.$playlistContent['act'].$input_end.'>';
    if($editable | $show_introduction) {
        $outputHTML = $outputHTML.'<span class="introduction songlist">';
        $outputHTML = $outputHTML.'<'.$textarea.' class="introduction_text" placeholder="Introduction text">'.(string) $playlistContent->introduction.'</'.$textarea.'>';
        if($show_duration) {
            $outputHTML = $outputHTML.'<'.$input_start.' class="introduction_duration" placeholder="Introduction duration"'.$input_middle.$playlistContent->introduction['duration'].$input_end.'>';
        }
        $outputHTML = $outputHTML.'</span>';
    }
    $outputHTML = $outputHTML.'<ul>';
    foreach ($playlistContent->set as $thisSet) {
        $set_duration = 0;
        $setHTML = '';
        foreach($thisSet->song as $thisSong) {
            $set_duration = $set_duration + sbk_duration_string_to_seconds((string) $thisSong['duration']) + sbk_duration_string_to_seconds((string) $thisSong->introduction['duration']);
            $setHTML = $setHTML.sbk_song_as_li($thisSong, $textarea, $input_start, $input_middle, $input_end, $editable, $show_key, $show_capo, $show_singer, $show_id, $show_writtenby, $show_performedby, $show_duration, $show_introduction);
        }
        $outputHTML = $outputHTML.'<li class="set playlist">';
        $outputHTML = $outputHTML.'<'.$input_start.' class="set-title"'.$input_middle.$thisSet['label'].$input_end.'>';
        if($show_duration) {
            $outputHTML = $outputHTML.'<span class="duration">'.sbk_seconds_to_duration_string($set_duration).'</span>';
        }
        if($editable | $show_introduction) {
            $outputHTML = $outputHTML.'<span class="introduction set">';
            $outputHTML = $outputHTML.'<'.$textarea.' class="introduction_text" placeholder="Introduction text">'.(string) $thisSet->introduction.'</'.$textarea.'>';
            if($show_duration) {
                $outputHTML = $outputHTML.'<'.$input_start.' class="introduction_duration" placeholder="Introduction duration"'.$input_middle.$thisSet->introduction['duration'].$input_end.'>';
            }
            $outputHTML = $outputHTML.'</span>';
        }
        $outputHTML = $outputHTML.'<ol>';
        $outputHTML = $outputHTML.$setHTML;
        if($editable) {
            $outputHTML = $outputHTML.'<li class="dummy">&nbsp;</li>';
        }
        $outputHTML = $outputHTML.'</ol>';
        $outputHTML = $outputHTML.'</li>';
    }
    $outputHTML = $outputHTML.'</ul>';
    return $outputHTML;
}

function sbk_convert_parsedjson_to_playlistXML($parsed_json) {
    $playlistContent = new SimpleXMLElement('<?xml version="1.0" standalone="yes"?><songlist></songlist>');
    $playlistContent->addAttribute('title', $parsed_json->title);
    $playlistContent->addAttribute('act', $parsed_json->act);

    $show_introduction = $playlistContent->addChild('introduction', (string) $parsed_json->introduction->text);
    $show_introduction->addAttribute('duration',(string) $parsed_json->introduction->duration);

    foreach($parsed_json->sets as $thisSet) {
        $set_duration = 0;
        $XMLset = $playlistContent->addChild('set');
        $XMLset->addAttribute('label', $thisSet->label);

        $set_introduction = $XMLset->addChild('introduction', (string) $thisSet->introduction->text);
        $set_introduction->addAttribute('duration',(string) $thisSet->introduction->duration);

        foreach($thisSet->songs as $thisSong) {
            $this_id = (string) $thisSong->id;
            $this_id = str_replace('id_', '', $this_id);
            if(is_numeric($this_id)) {
                $set_duration = $set_duration + sbk_duration_string_to_seconds((string) $thisSong->duration);

                $XMLsong = $XMLset->addChild('song', '');
                $XMLsong->addAttribute('id', $this_id);
                $XMLsong->addAttribute('key',(string) $thisSong->key);
                $XMLsong->addAttribute('singer',(string) $thisSong->singer);
                $XMLsong->addAttribute('capo',(string) $thisSong->capo);
                $XMLsong->addAttribute('duration',(string) $thisSong->duration);

                if($thisSong->introduction) {
                    $introduction = $XMLsong->addChild('introduction', (string) $thisSong->introduction->text);
                    $introduction->addAttribute('duration',(string) $thisSong->introduction->duration);
                }
            }
        }
    }

    return $playlistContent;
}

function sbk_duration_string_to_seconds($individual_duration_string) {
    if($individual_duration_string !== '') {
        //assumes duration is mm:ss - so less than a minute would be 00:ss
        $time_bits = preg_split('/:/', $individual_duration_string);
        $duration_seconds = $time_bits[0] * 60 + $time_bits[1];
    } else {
        $duration_seconds = 0;
    }
    return $duration_seconds;
}

function sbk_seconds_to_duration_string($duration_seconds) {
    $exact = $duration_seconds/60;
    $hours = floor($exact);
    $minutes = ($exact - $hours) * 60;

    return str_pad($hours, 2, '0', STR_PAD_LEFT).':'.str_pad($minutes, 2, '0', STR_PAD_LEFT);
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
        $result = acradb_get_query_result("select * from ".SBK_TABLE_NAME.$searchWHERE." order by `title`", SBK_DATABASE_NAME);
        $outputHTML = '';
        $outputHTML = $outputHTML.'<div id="allsongs">';
        $outputHTML = $outputHTML.'<span class="numberofrecords">'.mysql_num_rows($result).'</span>';
        $outputHTML = $outputHTML.'<ul>';
        while ($this_record = mysql_fetch_assoc($result)) {
            $outputHTML = $outputHTML.sbk_song_as_li($this_record);
    	}
        $outputHTML = $outputHTML.'</ul>';
    	$outputHTML = $outputHTML.'</div>';
    	return $outputHTML;
}

function sbk_getIDarray($playlistXML = false) {
    $ID_array = Array();
    if($playlistXML) {
        foreach($playlistXML->set as $this_set) {
            foreach($this_set->song as $this_song_playlist_data) {
                if(!array_key_exists($this_song_playlist_data['id'], $ID_array)) {
                    $ID_array[(string) $this_song_playlist_data['id']] = $this_song_playlist_data;
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

function sbk_section_html($sort_fieldname) {
    $html = '';

    $html = $html.'<div class="indent">';
    ksort($index[$sort_fieldname]);
    foreach($index[$sort_fieldname] as $this_field => $this_songarray) {
        $html = $html.'<h2>'.ucwords($this_field).'</h2>';
        ksort($this_songarray);
        foreach($this_songarray as $this_title => $this_html) {
            $html = $html.$this_html;
        }
    }
    $html = $html.'</div>';

    return $html;
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

    foreach ($ID_array as $this_id => $this_song_playlist_data) {
        $id_csv = $id_csv.$this_id.", ";
    }
    $id_csv = substr($id_csv, 0, -2);
    $id_csv = $id_csv.")";

    $line_count = 0; //a line is a heading, a song, a space between paragraphs, a main heading is 2 lines
    $column_height = 20; //lines - songs or headings
    $page_width = 3; //columns

    $result = acradb_get_query_result("select * from ".SBK_TABLE_NAME." WHERE id IN ".$id_csv, SBK_DATABASE_NAME);
    while ($this_record = mysql_fetch_assoc($result)) {

        $this_title = trim($this_record['title']);
        $this_html = '<div class="song" id="'.$this_record['id'].'"><span class="id">'.$this_record['id'].'</span><span class="title">'.$this_record['title'].'</span></div>';

        $index['title'][$this_title] = $this_html;

        $parts = explode(",", $this_record['written_by']);
        foreach($parts as $this_writtenby) {
            $this_writtenby = trim($this_writtenby);
            if($this_writtenby !== '') {
                $index['writtenby'][trim($this_writtenby)][$this_title] = $this_html;
            }
        }

        $parts = explode(",",$this_record['performed_by']);
        foreach($parts as $this_performedby) {
            $this_performedby = trim($this_performedby);
            if($this_performedby !== '') {
                $index['performedby'][trim($this_performedby)][$this_title] = $this_html;
            }
        }

        $parts = explode(",",$this_record['meta_tags']);
        foreach($parts as $this_category) {
            $this_category = trim($this_category);
            if($this_category !== '') {
                $index['categories'][trim($this_category)][$this_title] = $this_html;
            }
        }
    }

    $html = $html.'<h1>Sorted by title</h1>';
    $line_count = $line_count + 1;
    $html = $html.'<div class="indent">';
    ksort($index['title']);

    foreach($index['title'] as $this_title => $this_html) {
        if($line_count % $column_height === 0) {

        }
        $html = $html.$this_html;
    }
    $html = $html.'</div>';

    $html = $html.'<h1>Sorted by composer</h1>';
    $html = $html.'<div class="indent">';
    ksort($index['writtenby']);
    foreach($index['writtenby'] as $this_composer => $this_songarray) {
        $html = $html.'<h2>'.ucwords($this_composer).'</h2>';
        ksort($this_songarray);
        foreach($this_songarray as $this_title => $this_html) {
            $html = $html.$this_html;
        }
    }
    $html = $html.'</div>';

    $html = $html.'<h1>Sorted by performer</h1>';
    $html = $html.'<div class="indent">';
    ksort($index['performedby']);
    foreach($index['performedby'] as $this_performer => $this_songarray) {
        $html = $html.'<h2>'.ucwords($this_performer).'</h2>';
        ksort($this_songarray);
        foreach($this_songarray as $this_title => $this_html) {
            $html = $html.$this_html;
        }
    }
    $html = $html.'</div>';

    $html = $html.'<h1>Sorted by category</h1>';
    $html = $html.'<div class="indent">';
    ksort($index['categories']);
    foreach($index['categories'] as $this_category => $this_songarray) {
        $html = $html.'<h2>'.ucwords($this_category).'</h2>';
        $html = $html.'<div class="indent">';
        ksort($this_songarray);
        foreach($this_songarray as $this_title => $this_html) {
            $html = $html.$this_html;
        }
        $html = $html.'</div>';

    }
    $html = $html.'</div>';

    return '<div class="song-index">'.$html.'</div>';
}

function sbk_create_blank_playlist($filename) {
    $playlistContent = new SimpleXMLElement('<?xml version="1.0" standalone="yes"?><songlist title="(Enter a title for the PLAYLIST here)"><set label="(Enter a label for this SET here)"></set></songlist>');
    return $playlistContent->saveXML(PLAYLIST_DIRECTORY.'/'.$filename.'.playlist');
}

function sbk_chord_replace_callback($chord, $base_key = null, $target_key = null) {
    if(!is_null($base_key) && !is_null($target_key) && $base_key !== '' && $target_key !== '') {
        $chord = sbk_transpose_chord($chord, $base_key, $target_key);
    }
    return '</span><span class="chord">'.$chord.'</span><span class="text">';
}

function sbk_convert_song_content_to_HTML($content, $base_key = 'null', $target_key = null) {
    if(is_null($target_key)) {
        $params = '';
    } else {
        $params  = ', "'.$base_key.'", "'.$target_key.'"';
    }
    $contentHTML = $content;
    $contentHTML = preg_replace('/&([^#n])/', '&#38;$1', $contentHTML);
    $contentHTML = str_replace(' ', '&#160;', $contentHTML);
    $contentHTML = preg_replace('/\n/','</span></div><div class="line"><span class="text">', $contentHTML);
    $contentHTML = preg_replace('/<div class=\"line\"><span class=\"text\">[\s]*?<\/span><\/div>/', '<div class="line"><span class="text">&nbsp;</span></div>', $contentHTML);
    $contentHTML = preg_replace_callback('/\[(.*?)\]/', create_function('$matches', 'return sbk_chord_replace_callback($matches[1]'.$params.');'), $contentHTML);
    $contentHTML = preg_replace('#<span class="chord">([^<]*?)/([^<]*?)</span>#','<span class="chord">$1<span class="bass_note_modifier separator">/</span><span class="bass_note_modifier note">$2</span></span>', $contentHTML);
    $contentHTML = preg_replace('/&nbsp;/', '&#160;', $contentHTML); //&nbsp; doesn't work in XML unless it's specifically declared.
    $contentHTML = '<div class="content"><div class="line"><span class="text">'.$contentHTML.'</span></div></div>';

    return $contentHTML;
}

function sbk_playlist_as_editable_html($playlist) {
    $display = '';
    $playlistContent = simplexml_load_file(PLAYLIST_DIRECTORY.'/'.$playlist.'.playlist');
    $display = $display.sbk_convert_playlistXML_to_editable_list($playlistContent);

    return $display;
}

function sbk_get_song_record($id) {
    return acradb_get_single_record(SBK_DATABASE_NAME, SBK_TABLE_NAME, SBK_KEYFIELD_NAME, $id);
}

function sbk_get_song_html($id, $key = null, $singer = null, $capo = null) {
    $display = '';
    if($key === '') {
        $key = null;
    }
    if($singer === '') {
        $singer = null;
    }
    if($capo === '') {
        $capo = null;
    }
    $this_record = sbk_get_song_record($id);
    $display = sbk_song_html($this_record, $key, $singer, $capo);

    return $display;
}

function sbk_song_html($this_record, $key = null, $singer = null, $capo = null) {

    $display = '';
    $number_of_lyric_lines_per_page = 55;
    $number_of_columns_per_page = 2;
    $target_key = null;
    if(is_null($key)) {
        $key = $this_record['base_key'];
    } else {
        if(is_integer($capo)) {
            $target_key = sbk_shift_note($key, -1 * $capo);
        } else {
            $target_key = $key;
        }
    }
    if(is_null($this_record['base_key'])) {
        if(!is_null) {
            p("sbk_song_html() :: This song ( no. ".$this_record['id'].", ".$this_record['title'].") does not have a base key specified. It will not be transposed");
        }
    }
    $contentHTML = sbk_convert_song_content_to_HTML($this_record['content'], $this_record['base_key'], $target_key);

    $contentXML = new SimpleXMLElement($contentHTML);
    $line_count = 0;
    $page_index = 0;
    $pageXML = array();
    $pageXML[$page_index] = new SimpleXMLElement('<div class="song-page first_page" id="page_'.$this_record['id'].'_'.$page_index.'"></div>');
    $table_row = $pageXML[$page_index]->addChild('table')->addChild('tr');
    $column_count = -2;
    $current_column = $table_row->addChild('td');
    $dom_current_column = dom_import_simplexml($current_column);
    $this_column_height = 0;
    $previous_column_height = 0;

   foreach($contentXML->xpath('//div[@class="line"]') as $this_line) {
       if(sizeof($this_line->xpath('span[@class="chord"]')) > 0) {
        $line_spacing = 1.7;
       } else {
        $line_spacing = 1;
       }
       $line_count = $line_count + $line_spacing;
       //p($line_count, $number_of_lyric_lines_per_page, $column_count, $number_of_columns_per_page, $line_count % $number_of_lyric_lines_per_page, $column_count % $number_of_columns_per_page);
       $this_column_height = ($line_count % $number_of_lyric_lines_per_page);
       $previous_page_width = 0;
       if($this_column_height < $previous_column_height) {
           //too many lines - start a new column
           $this_page_width = ($column_count % $number_of_columns_per_page);
            if($this_page_width < $previous_page_width) {
                //too many columns - start a new page
                $page_index = $page_index + 1;
                $pageXML[$page_index] = new SimpleXMLElement('<div class="song-page page_'.$page_index.'"></div>');
                $table_row = $pageXML[$page_index]->addChild('table')->addChild('tr');
                $column_count = -2;
                $dom_current_column = dom_import_simplexml($current_column);
            }
            $current_column = $table_row->addChild('td');
            $column_count = $column_count + 1;
            $dom_current_column = dom_import_simplexml($current_column);
            $previous_page_width = $this_page_width;
        }
        $dom_line = dom_import_simplexml($this_line);
        $dom_line = $dom_current_column->ownerDocument->importNode($dom_line, true);
        $dom_current_column->appendChild($dom_line);
        $previous_column_height = $this_column_height;
    }

    $page_header = '<div class="page_header">';
    $page_header = $page_header.'<div class="title">'.$this_record['title'].'</div>';
    $page_header = $page_header.'<span class="songnumber"><span class="label">Song no. </span><span class="data">'.$this_record['id'].'</span></span>';
    $page_header = $page_header.'<span class="pagenumber"><span class="label">page</span><span class="data" id="page_number">test</span><span class="label">of</span><span class="data" id="number_of_pages">test</span></span>';
    $page_header = $page_header.'<div class="written_by"><span class="data">'.str_replace('&', 'and', $this_record['written_by']).'</span></div>';
    $page_header = $page_header.'<div class="performed_by"><span class="label">performed by: </span><span class="data">'.$this_record['performed_by'].'</span></div>';

    $page_header = $page_header.'<div class="key"> ';//space needed here, or else if there's nulls you can end up with '<div class="key"/></div>', whcih messes up the page
    if(!is_null($singer)) {
        $page_header = $page_header.'<div class="singer"><span class="label">chords for </span><span class="data">'.$singer.'</span></div>';
    }
    if(!is_null($capo)) {
        $page_header = $page_header.'<div class="capo"><span class="label">Capo </span><span class="data">'.$capo.'</span></div>';
        $page_header = $page_header.'<div class="target_key">(<span class="label">actual key: </span><span class="data">'.$key.'</span>)</div>';
    } else {
        $page_header = $page_header.'<div class="target_key"><span class="label">key: </span><span class="data">'.$key.'</span></div>';
    }
    $page_header = $page_header.'</div>';

    $page_header = $page_header.'</div>';
    $page_headerXML = new SimpleXMLElement($page_header);

    $page_number = 0;
    foreach($pageXML as $page_contentXML) {
        $page_number = $page_number + 1;

        $page_number_holder = $page_headerXML->xpath('//span[@id="page_number"]');
        $number_of_pages_holder = $page_headerXML->xpath('//span[@id="number_of_pages"]');
        dom_import_simplexml($page_number_holder[0])->nodeValue = $page_number;
        dom_import_simplexml($number_of_pages_holder[0])->nodeValue = ($page_index + 1);

        $dom_page = dom_import_simplexml($page_contentXML);
        $dom_header = dom_import_simplexml($page_headerXML);
        $dom_header = $dom_page->ownerDocument->importNode($dom_header, true);
        $dom_page->insertBefore($dom_header, $dom_page->firstChild);

        $display = $display.str_replace('<?xml version="1.0"?'.'>', '', $page_contentXML->asXML());
        $display = str_replace("<span class=\"text\">\n</span>", '&nbsp;', $display);//the &#160; got lost somewhere along the way (DOM part?) and <span>&nbsp;</span> doesn't display on screen
    }

    return $display;
}

function sbk_print_multiple_songs($id_array) {
    $output = '';

    foreach ($id_array as $this_id => $this_song_playlist_data) {
        foreach($this_song_playlist_data->attributes() as $attribute_name => $value) {
            $attributes[$attribute_name] = (string) $value;
        }
        $output = $output.sbk_get_song_html($attributes['id'], $attributes['key'], $attributes['singer'], $attributes['capo']);
    }

    return $output;
}

function sbk_output_pdf($display, $title = '', $orientation = 'portrait') {
        $pdf = new WKPDF();
        $pdf->set_orientation($orientation);
        $display = '<html><head><title>'.$title.'</title><link href="../index.css" rel="stylesheet" type="text/css" /></head><body class="pdf">'.$display.'</body></html>';
        $display = preg_replace('/&nbsp;/', '&#160;', $display); //&nbsp; doesn't work in XML unless it's specifically declared.
        $pdf->set_html($display);
        $pdf->render();
        $pdf->output(WKPDF::$PDF_DOWNLOAD, $title.".pdf");
}
/**
 *
 * Convert a song node or database record to a single li
 * @param simpleXML_node_or_array $thisSong
 * @param string $textarea
 * @param string $input_start
 * @param string $input_middle
 * @param string $input_end
 * @param boolean $editable
 * @param boolean $show_key
 * @param boolean $show_singer
 * @param boolean $show_id
 * @param boolean $show_writtenby
 * @param boolean $show_performedby
 * @param boolean $show_duration
 * @param boolean $show_introduction
 */
function sbk_song_as_li(
            $thisSong,
            $textarea = 'span',
            $input_start = 'span',
            $input_middle = '>',
            $input_end = '</span',
            $editable = false,
            $show_key = TRUE,
            $show_capo = TRUE,
            $show_singer = TRUE,
            $show_id = TRUE,
            $show_writtenby = TRUE,
            $show_performedby = TRUE,
            $show_duration = true,
            $show_introduction = true) {
    //capture('thisSong', 'textarea', 'input_start', 'input_end', 'editable', 'show_key', 'show_singer', 'show_id', 'show_writtenby', 'show_performedby', 'show_duration', 'show_introduction');

    $key = '';
    $capo = '';
    $singer = '';
    $songDuration = '';
    if(gettype($thisSong) === 'object') {
        //p(gettype($thisSong), $thisSong->asXML());
        //p($thisSong['duration']);
        $key = (string) $thisSong['key'];
        $capo = (string) $thisSong['capo'];
        $singer = (string) $thisSong['singer'];
        $songDuration = (string) $thisSong['duration'];
    } else {
        if(array_key_exists('key', $thisSong)) {
            $key = $thisSong['key'];
        }
        if(array_key_exists('capo', $thisSong)) {
            $capo = $thisSong['capo'];
        }
        if(array_key_exists('singer', $thisSong)) {p($thisSong);
            $singer = $thisSong['singer'];
        }
        if(array_key_exists('duration', $thisSong)) {
            $songDuration = $thisSong['duration'];
        }
    }
/**
@todo
Handle other attributes when it's an array
 */
    $setHTML = '';
    $this_record = acradb_get_single_record('music_admin', 'lyrics', 'id', $thisSong['id']);
    $songHTML = array();
    $songHTML['key'] = '';
    $songHTML['capo'] = '';
    $songHTML['singer'] = '';
    $songHTML['id'] = '';
    $songHTML['songDuration'] = '';
    $songHTML['writtenBy'] = '';
    $songHTML['performedBy'] = '';
    $songHTML['introduction_text'] = '';
    $songHTML['introduction_duration'] = '';
    $songHTML['title'] = '<span class="title">'.$this_record['title'].'</span>';
    if($show_key) {
        $songHTML['key'] = '<'.$input_start.' class="key" placeholder="key"'.$input_middle.$key.$input_end.'>';
    }
    if($show_singer) {
        $songHTML['singer'] = '<'.$input_start.' class="singer" placeholder="singer"'.$input_middle.$singer.$input_end.'>';
    }
    if($show_capo) {
        $songHTML['capo'] = '<'.$input_start.' class="capo" placeholder="capo"'.$input_middle.$capo.$input_end.'>';
    }
    if($show_id) {
        $songHTML['id'] = '<span class="id">'.$this_record['id'].'</span>';
    }
    if($show_duration) {
        $songHTML['songDuration'] = '<'.$input_start.' class="duration" placeholder="mm:ss"'.$input_middle.$songDuration.$input_end.'>';
    }
    if($show_writtenby) {
        $songHTML['writtenBy'] = '<span class="written_by">'.$this_record['written_by'].'</span>';
    }
    if($show_performedby) {
        if($show_writtenby) {
            $songHTML['performedBy'] = ' | ';
        }
        $songHTML['performedBy'] .= '<span class="performed_by">'.$this_record['performed_by'].'</span>';
    }
    if(array_key_exists('introduction', $thisSong)) {
        $songHTML['introduction_text'] = '<'.$textarea.' class="introduction_text" placeholder="Introduction text">'.(string) $thisSong->introduction.'</'.$textarea.'>';
    } else {
        $songHTML['introduction_text'] = '<'.$textarea.' class="introduction_text" placeholder="Introduction text">'.'</'.$textarea.'>';
    }
    if($show_duration) {
        if(array_key_exists('introduction', $thisSong)) {
            $songHTML['introduction_duration'] = '<'.$input_start.' class="introduction_duration" placeholder="Introduction duration"'.$input_middle.$thisSong->introduction['duration'].$input_end.'>';
        } else {
            $songHTML['introduction_duration'] = '<'.$input_start.' class="introduction_duration" placeholder="Introduction duration"'.$input_middle.$input_end.'>';
        }
    }

    $setHTML = $setHTML.'<li class="song" id="id_'.$this_record['id'].'">';
    if($editable) {
        $setHTML = $setHTML.$songHTML['singer'];
        $setHTML = $setHTML.$songHTML['key'];
        $setHTML = $setHTML.$songHTML['capo'];
        $setHTML = $setHTML.$songHTML['id'];
        $setHTML = $setHTML.$songHTML['songDuration'];
        $setHTML = $setHTML.$songHTML['title'];
        $setHTML = $setHTML.'<span class="introduction">';
            $setHTML = $setHTML.$songHTML['introduction_text'];
            $setHTML = $setHTML.$songHTML['introduction_duration'];
        $setHTML = $setHTML.'</span>';
    } else {
        $setHTML = $setHTML.$songHTML['title'];
        if($show_writtenby | $show_performedby) {
            $setHTML = $setHTML.'<span class="detail"> (';
            $setHTML = $setHTML.$songHTML['writtenBy'];
            $setHTML = $setHTML.$songHTML['performedBy'];
            $setHTML = $setHTML.')</span>';
        }
        if($show_key | $show_singer | $show_id | $show_capo) {
            $setHTML = $setHTML.'<span class="spec">';
            $setHTML = $setHTML.$songHTML['key'];
            $setHTML = $setHTML.$songHTML['singer'];
            $setHTML = $setHTML.$songHTML['capo'];
            $setHTML = $setHTML.$songHTML['id'];
            $setHTML = $setHTML.$songHTML['songDuration'];
            $setHTML = $setHTML.'</span>';
        }
        if($show_introduction) {
            $setHTML = $setHTML.'<span class="introduction">';
            $setHTML = $setHTML.$songHTML['introduction_text'];
            $setHTML = $setHTML.$songHTML['introduction_duration'];
            $setHTML = $setHTML.'</span>';
        }
    }
    $setHTML = $setHTML.'</li>';

    return $setHTML;
}


function sbk_song_edit_form ($id, $playlist = false, $all_fields_editable = true) {
    $display ='';

    $display = $display.'<form id="edit-song-form" action = "?action=displaySong" method="post">';

    if($id) {
        $display = $display.'<input type="hidden" name="update" id="update" value="editExistingSong"></input>';
        $display = $display.'<input type="hidden" name="display_id" id="display-id" value="'.$id.'"></input>';
        $this_record = acradb_get_single_record('music_admin', 'lyrics', 'id', $id);

        $display = $display.'<div class="song_id"><span class="label">Song ID: </span>['.$id.']</div>';
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

    if($playlist) {
        $display = $display.'<input type="hidden" name="playlist" id="update" value="'.$_GET['playlist'] .'"></input>';
    }

     if($all_fields_editable) {
        $textarea = 'textarea';
    } else {
        $textarea = 'span';
    }

    $display = $display.'<div class="title"><span class="label">title: </span>                                 <'.$textarea.' name="title"             id="title"            >'.$this_record['title'].            '</'.$textarea.'></div>';
    $display = $display.'<div class="performed_by"><span class="label">performed by: </span>                   <'.$textarea.' name="performed_by"      id="performed_by"     >'.$this_record['performed_by'].     '</'.$textarea.'></div>';
    $display = $display.'<div class="written_by"><span class="label">written by: </span>                       <'.$textarea.' name="written_by"        id="written_by"       >'.$this_record['written_by'].       '</'.$textarea.'></div>';
    $display = $display.'<div class="base_key"><span class="label">base_key: </span>                           <'.$textarea.' name="base_key"          id="base_key"         >'.$this_record['base_key'].         '</'.$textarea.'></div>';
    $display = $display.'<a id="remove_linebreaks" href="#">Remove double linebreaks</a><a id="add_chords" href="#">Enter Chords</a>';
    $display = $display.'<div class="content"><'.'textarea   name="content"           id="content"          >'.$this_record['content'].          '</'.'textarea'.'></div>';
    $display = $display.'<div class="meta_tags"><span class="label">sort terms: </span>                        <'.$textarea.' name="meta_tags"         id="meta_tags"        >'.$this_record['meta_tags'].        '</'.$textarea.'></div>';
    //$display = $display.'<div class="original_filename"><span class="label">orignal filename: </span>                                                     <'.$textarea.' name="original_filename" id="original_filename">'.$this_record['original_filename'].'</'.$textarea.'></div>';
    $display = $display.'<input type=submit value="Save changes" />';
    $display = $display.'</form>';

    return $display;
}

function sbk_find_note_number($original_value, $adjustment) {
    $new_value = $original_value + $adjustment;
    if($new_value < 0) {
        $new_value = 12 + $new_value;
    }
    if($new_value > 11) {
        $new_value = $new_value - 12;
    }
    return $new_value;
}

function sbk_note_to_upper($note) {
    $base_note = substr($note, 0, 1);
    $upper_note = strtoupper($base_note);
    if(strlen($note) == 2) {
        $upper_note = $upper_note.substr($note, 1, 1);
    }
    return $upper_note;
}

function sbk_note_to_lower($note) {
    $base_note = substr($note, 0, 1);
    $lower_note = strtolower($base_note);
    if(strlen($note) == 2) {
        $lower_note = $lower_note.substr($note, 1, 1);
    }
    return $lower_note;
}

function sbk_shift_note($note, $adjustment, $use_sharps = null) {
    global $NOTE_VALUE_ARRAY, $VALUE_NOTE_ARRAY_DEFAULT, $VALUE_NOTE_ARRAY_SHARP, $VALUE_NOTE_ARRAY_FLAT;

    $lowercase = false;
    if(sbk_note_to_lower($note) === $note) {
        $lowercase = true;
    }
    $new_note_number = sbk_find_note_number($NOTE_VALUE_ARRAY[sbk_note_to_upper($note)], $adjustment);
    if(is_null($use_sharps)) {
        $new_note = $VALUE_NOTE_ARRAY_DEFAULT[$new_note_number];
    } elseif($use_sharps === true) {
        $new_note = $VALUE_NOTE_ARRAY_SHARP[$new_note_number];
    } else {
        $new_note = $VALUE_NOTE_ARRAY_FLAT[$new_note_number];
    }
    if($lowercase) {
        $new_note = sbk_note_to_lower($new_note);
    }
    return $new_note;
}

function sbk_transpose_chord($chord, $base_key, $target_key) {
    global $NOTE_VALUE_ARRAY, $VALUE_NOTE_ARRAY_DEFAULT, $VALUE_NOTE_ARRAY_SHARP, $VALUE_NOTE_ARRAY_FLAT;

    if(is_null($base_key) || $base_key === '') {
        throw new Exception("sbk_transpose_chord() :: no base key passed");
    }
    if(is_null($target_key) || $target_key === '') {
        throw new Exception("sbk_transpose_chord() :: no target key passed");
    }
    $chord_note = substr($chord, 0, 1);
    $second_char = substr($chord, 1, 1);
    $modifier_start = 1;
    if($second_char === '#' || $second_char == 'b') {
        $chord_note = $chord_note.$second_char;
        $modifier_start = 2;
    }
    $chord_modifier = substr($chord, $modifier_start);

    $key_conversion_value = $NOTE_VALUE_ARRAY[$target_key] - $NOTE_VALUE_ARRAY[$base_key];
    $new_chord = sbk_shift_note($chord_note, $key_conversion_value);

    $bass_key = '';
    $slash_position = strpos($chord_modifier, '/');
    if($slash_position !== false) {
        $new_chord_modifier = substr($chord_modifier, 0, $slash_position);
        $old_bass_key = substr($chord_modifier, $slash_position + 1);
        $new_bass_key = sbk_shift_note($old_bass_key, $key_conversion_value);
        $bass_key = '/'.$new_bass_key;
    } else {
        $new_chord_modifier = $chord_modifier;
    }

    $new_chord = $new_chord.$new_chord_modifier.$bass_key;

    return $new_chord;

}
?>
