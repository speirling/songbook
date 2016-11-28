
<nav class="large-2 medium-2 columns" id="actions-sidebar">
    <?= $this->element('standard_menu') ?>

    <ul class="side-nav">
        <li><?= $this->Html->link(__('Edit Event'), ['action' => 'edit', $event->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Event'), ['action' => 'delete', $event->id], ['confirm' => __('Are you sure you want to delete # {0}?', $event->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Events'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Event'), ['action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Song Performances'), ['controller' => 'SongPerformances', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Song Performance'), ['controller' => 'SongPerformances', 'action' => 'add']) ?> </li>
    </ul>
</nav>
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
        <?php if (!empty($event_songs)): ?>
        <table cellpadding="0" cellspacing="0">
            <?php foreach ($event_songs as $this_performance): ?>
                <?= $this->element('song_row', [ 
                    'return_point' => ['controller'=>'events', 'method'=>'view', 'id'=>$event->id],
                    'current_song' => $this_performance->song,
                    'this_set_songs' => $this_performance->set_songs
                    ]); 
                ?>
            <?php endforeach; ?>
        </table>
    <?php endif; ?>
    </div>
</div>
