
<div class="filtered-songlist">
<?php if($print_title !== '') { ?>
	<span class="filter-title"><?php 
	echo($print_title); 
	?></span>
<?php } ?>
    <ul id="songlist">
    <?php

    foreach ($filtered_list as $song){
        $primary_key = $song['base_key'];
        //debug($song);
        /* $song: id, title, written_by, performed_by, base_key, content */
        
        
        if(sizeof($song['set_songs']) > 0) {
            $primary_key = $song['set_songs'][0]['key'];
            $primary_capo = $song['set_songs'][0]['capo'];
        } else {
            $primary_key = false;
            $primary_capo = false;
        }
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
    ?>
    </ul>
    
</div>