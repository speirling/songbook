<?php /* Template/Events/view.ctp */ 
    $controller_name = 'Event';
    echo($this->element('standard_menu', ['controller_name' => $controller_name]) );
?>
<div class="events view large-9 medium-8 columns content">
    <h3>#<?= h($event->id) ?> : <?= h($event->venue) ?></h3>
    <table class="vertical-table">
        <tr>
            <th><?= __('Duration Hours') ?></th>
            <td><?= $this->Number->format($event->duration_hours) ?></td>
        </tr>
        <tr>
            <th><?= __('Timestamp') ?></th>
            <td><?= h($event->timestamp) ?></tr>
        </tr>
    </table>
    <div class="row">
        <h4><?= __('Notes') ?></h4>
        <?= $this->Text->autoParagraph(h($event->notes)); ?>
    </div>
    <div class="related">
        <h4><?= __('Related Song Performances') ?></h4>
        <?php if (!empty($filtered_list)): ?>
        <table cellpadding="0" cellspacing="0">
            <tbody>
            <?php 
            foreach ($filtered_list as $current_song){
                if ($search_string == "") {
                    $return_port_id = " ";
                } else {
                    $return_port_id = $search_string;
                }
                echo $this->element('song_row', [
                    'current_song' => $current_song,
                    'this_set_songs' => $current_song->set_songs,
                    'performers_list' => $performers
                ]); 
             } ?>  
        </tbody>
        </table>
    <?php endif; ?>
    </div>
</div>
