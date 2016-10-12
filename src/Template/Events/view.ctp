<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <?= $this->Form->postLink(__('Delete Event'), ['action' => 'delete', $event->id], ['confirm' => __('Are you sure you want to delete # {0}?', $event->id)]) ?>
    <?= $this->element('standard_menu') ?>
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
</div>
