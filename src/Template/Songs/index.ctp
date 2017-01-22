<?php /* Template/Songs/index.ctp */  
    $controller_name = 'Song';
    echo($this->element('standard_menu', ['controller_name' => $controller_name]) );
?>
<div class="songs index large-9 medium-9 columns content">

    <h3><?= __('Songs') ?></h3>
    <table cellpadding="0" cellspacing="0">
        
        <tbody>
            <?php foreach ($songs as $song){ 
            if ($search_string == "") {
                $return_port_id = " ";
            } else {
                $return_port_id = $search_string;
            }
                echo $this->element('song_row', [ 
                    'return_point' => ['controller'=>'songs', 'method'=>'search', 'id'=>$return_port_id],
                    'current_song' => $song,
                    'this_set_songs' => $song->set_songs,
                	'set_song_object' => $setSong,
                	'performers_list' => $performers
                ]); 
             } ?>  
        </tbody>
    </table>
    <div class="paginator">
        <ul class="pagination">
            <?= $this->Paginator->prev('< ' . __('previous')) ?>
            <?= $this->Paginator->numbers() ?>
            <?= $this->Paginator->next(__('next') . ' >') ?>
        </ul>
        <p><?= $this->Paginator->counter() ?></p>
    </div>
</div>
