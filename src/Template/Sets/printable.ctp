<?php /* Template/Sets/printable.php */ ?>

    <h3><?= h($set->title) ?></h3>
    <table class="vertical-table set-header">
        <tr>
            <th><?= __('Title') ?></th>
            <td><?= h($set->title) ?></td>
        </tr>
        <tr>
            <th><?= __('Performer') ?></th>
            <td><?= $set->has('performer') ? $this->Html->link($set->performer->name, ['controller' => 'Performers', 'action' => 'view', $set->performer->id]) : '' ?></td>
        </tr>
        <tr>
            <th><?= __('Id') ?></th>
            <td><?= $this->Number->format($set->id) ?></td>
        </tr>
    </table>
    
    
        <?php  
        //debug($set); 
        if (!empty($set->set_songs)) {
            foreach ($set->set_songs as $setSong) {
                //debug($setSong->song);
                echo($setSong->song->html_pages) ;
		    } //endforeach 
        } //endif
        ?>

