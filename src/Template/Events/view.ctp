<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Event'), ['action' => 'edit', $event->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Event'), ['action' => 'delete', $event->id], ['confirm' => __('Are you sure you want to delete # {0}?', $event->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Events'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Event'), ['action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Song Performances'), ['controller' => 'SongPerformances', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Song Performance'), ['controller' => 'SongPerformances', 'action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="events view large-9 medium-8 columns content">
    <h3><?= h($event->id) ?></h3>
    <table class="vertical-table">
        <tr>
            <th><?= __('Venue') ?></th>
            <td><?= h($event->venue) ?></td>
        </tr>
        <tr>
            <th><?= __('Id') ?></th>
            <td><?= $this->Number->format($event->id) ?></td>
        </tr>
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
            <tr>
                <th><?= __('Id') ?></th>
                <th><?= __('Song Id') ?></th>
                <th><?= __('Title') ?></th>
            </tr>
            <?php foreach ($event_songs as $this_performance): ?>
            <tr>
                <td><?= h($this_performance->id) ?></td>
                <td><?= h($this_performance->song_id) ?></td>
                <td><?= h($this_performance->song->title) ?></td>
            </tr>
            <?php endforeach; ?>
        </table>
    <?php endif; ?>
    </div>
</div>
