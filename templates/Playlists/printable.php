<?php /* Template/Playlists/printable.php */ ?>

    <h3><?= h($playlist->title) ?></h3>
    <?= $playlist->has('performer') ? '<h4>Playlist for : ' . $playlist->performer->name . '</h4>'  : '<h4>Playlist</h4>' ?>
    
    
        <?php  
        if (!empty($playlist->playlist_sets)) {
            foreach ($playlist->playlist_sets as $playlist_set) {
                foreach ($playlist_set->set->set_songs as $setSong) {
                //debug($setSong->song->html_pages);
	                echo($setSong->song->html_pages) ;
			    } //endforeach
		    } //endforeach 
        } //endif
        else {echo "no sets"; }
        ?>

