<?php /* Template/Events/view.ctp */ 
    $controller_name = 'Event';
    echo($this->element('standard_menu', ['controller_name' => $controller_name]) );
?>
<div class="events view large-9 medium-8 columns content">
    <h3><?= h($event->venue) ?> <?= h($event->timestamp) ?></h3>
        <?php if (!empty($filtered_list)): ?>
        <table cellpadding="0" cellspacing="0">
            <thead>
            <tr>
                <td>Song ID</td>
                <td>Singer</td>
                <td>Keys</td>
                <td>Song Title</td>
                <td>Original Performer</td>
                <td>Tags</td>
            </tr>
            </thead>
            <tbody>
            <?php 
            foreach ($filtered_list as $current_song){
                if ($search_string == "") {
                    $return_port_id = " ";
                } else {
                    $return_port_id = $search_string;
                } ?>
                	   <tr>
                	   <td><?= h($current_song->id) ?></td>
                	    <td>
                	        <?php 
                	        $existing_performer_keys = [];
                	        $no_of_keys = 0;
                	        foreach ($current_song->set_songs as $set_song) {
                	            $performer_key = $set_song['performer']['nickname'].$set_song['key'];
                	            if (!in_array($performer_key, $existing_performer_keys)) {
                	            	array_push($existing_performer_keys, $performer_key);
                	            	if($no_of_keys > 0) {
                	            		echo " / ";
                	            	}
                	                echo $set_song['performer']['nickname'];
                	        		$no_of_keys = $no_of_keys + 1;
                	            }
                	        } ?>
                	    </td>
                        <td>
                	        <?php 
                	        $existing_performer_keys = [];
                	        $no_of_keys = 0;
                	        foreach ($current_song->set_songs as $set_song) {
                	            $performer_key = $set_song['performer']['nickname'].$set_song['key'];
                	            if (!in_array($performer_key, $existing_performer_keys)) {
                	            	array_push($existing_performer_keys, $performer_key);
                	            	if($no_of_keys > 0) {
                	            		echo " / ";
                	            	}
                	                echo $set_song['key'];
                	        		$no_of_keys = $no_of_keys + 1;
                	            }
                	        } ?>
                	    </td>
                	    <td><?= h($current_song->title) ?></td> 
                        <td>
                	    <?php if ($current_song->performed_by === '') {
                	            echo $current_song->written_by;
                	        } else {
                	            echo $current_song->performed_by;
                	    } ?>
                        </td>
                	    <td>
                	    <?php 
                	    if($current_song->song_tags) {
                	        $list_of_tags = '';
                	        foreach ($current_song->song_tags as $this_tag) {
                	            $list_of_tags = $list_of_tags . $this_tag->tag->title . ', ';
                	        }
                	        echo $list_of_tags;
                	    }
                	    ?>
                	    </td>
                	</tr>
                    <?php  
             } ?>  
        </tbody>
        </table>
    <?php endif; ?>
</div>
