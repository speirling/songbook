<?php
echo ('<div class="filtered-songlist">');
    if($print_title !== '') {
	    echo ('<span class="filter-title">'); 
    	echo($print_title); 
    	echo ('</span>');
    } 
    echo ('<ul id="songlist">');

    foreach ($filtered_list as $song){
        $performers_html='';
        $existing_performer_keys = [];
        foreach ($song['set_songs'] as $set_song) {
            $performer_key = $set_song['performer']['nickname'].$set_song['key'];
            if (!in_array($performer_key, $existing_performer_keys)) {
                array_push($existing_performer_keys, $performer_key);
                if($song['filter_on'] == false || $song['selected_performer'] == $set_song['performer']['id']) {
                    $performers_html = $performers_html . '<span class="performer short-form">';
                    $performers_html = $performers_html . '<span class="nickname">' . strtolower(substr($set_song['performer']['nickname'], 0, 1)) . '</span>';
                    $performers_html = $performers_html . '<span class="key">' . $set_song['key'] . '</span>';
                    $performers_html = $performers_html . '</span>';
                    
                    $primary_key = $set_song['key'];
                    $primary_capo = $set_song['capo'];
                }
            }
        } 
        echo('<li data-id="' . $song['id'] . '" data-key="' . $primary_key . '" data-capo="' . $primary_capo . '">');
        echo('<span class="song-title">' . $song['title'] . "</span>");
        echo($performers_html);
        echo('</li>');
    }
    echo ('</ul>');
    echo ('</div>');
?>