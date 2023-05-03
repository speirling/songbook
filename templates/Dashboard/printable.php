<?php /* Template/Dashboard/printable.ctp */  
    $controller_name = 'Dashboard';
?>

 

    <table cellpadding="0" cellspacing="0">
        <tbody>
            <?php 
            foreach ($filtered_list as $song){
                if ($search_string == "") {
                    $return_port_id = " ";
                } else {
                    $return_port_id = $search_string;
                }

                $display_row = true;
                if(sizeof($selected_exclude_tags) > 0) {
                    $no_of_excludables = 0;
                    foreach($selected_exclude_tags as $this_exclude_tag) {
                        foreach($song->song_tags as $this_tag) {
                            if($this_tag->tag->id ==  (int) $this_exclude_tag) {
                                $no_of_excludables = $no_of_excludables + 1;
                            }
                        }
                    }
                    if($exclude_all) {
	                    if($no_of_excludables == sizeof($selected_exclude_tags)) {
	                        $display_row = false;
	                    }
					} else {
	                    if($no_of_excludables > 0) {
	                        $display_row = false;
	                    }
					}
                }
                if($display_row) {
                    echo $this->element('song_row_printable', [
                        'current_song' => $song,
                        'this_set_songs' => $song->set_songs,
                        'performers_list' => $performers
                    ]);
                }
             } ?>  
        </tbody>
    </table>
    <?php if(!$filter_on) { ?>
    <div class="paginator">
        <ul class="pagination">
            <?= $this->Paginator->prev('< ' . __('previous')) ?>
            <?= $this->Paginator->numbers() ?>
            <?= $this->Paginator->next(__('next') . ' >') ?>
        </ul>
        <p><?= $this->Paginator->counter() ?></p>
    </div>
    <?php } ?>

