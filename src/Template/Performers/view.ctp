<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Performer'), ['action' => 'edit', $performer->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Performer'), ['action' => 'delete', $performer->id], ['confirm' => __('Are you sure you want to delete # {0}?', $performer->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Performers'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Performer'), ['action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Song Instances'), ['controller' => 'SongInstances', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Song Instance'), ['controller' => 'SongInstances', 'action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="performers view large-9 medium-8 columns content">
    <h3><?= h($performer->name) ?></h3>
    <table class="vertical-table">
        <tr>
            <th><?= __('Name') ?></th>
            <td><?= h($performer->name) ?></td>
        </tr>
        <tr>
            <th><?= __('Nickname') ?></th>
            <td><?= h($performer->nickname) ?></td>
        </tr>
        <tr>
            <th><?= __('Id') ?></th>
            <td><?= $this->Number->format($performer->id) ?></td>
        </tr>
    </table>
    <div class="related">
        <h4><?= __('Related Song Instances') ?></h4>
        <?php if (!empty($performer->song_instances)): ?>
        <table cellpadding="0" cellspacing="0">
            <tr>
                <th><?= __('Id') ?></th>
                <th><?= __('Song Id') ?></th>
                <th><?= __('Performer Id') ?></th>
                <th><?= __('Key') ?></th>
                <th class="actions"><?= __('Actions') ?></th>
            </tr>
            <?php foreach ($performer->song_instances as $songInstances): ?>
            <tr>
                <td><?= h($songInstances->id) ?></td>
                <td><?= h($songInstances->song_id) ?></td>
                <td><?= h($songInstances->performer_id) ?></td>
                <td><?= h($songInstances->key) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['controller' => 'SongInstances', 'action' => 'view', $songInstances->id]) ?>

                    <?= $this->Html->link(__('Edit'), ['controller' => 'SongInstances', 'action' => 'edit', $songInstances->id]) ?>

                    <?= $this->Form->postLink(__('Delete'), ['controller' => 'SongInstances', 'action' => 'delete', $songInstances->id], ['confirm' => __('Are you sure you want to delete # {0}?', $songInstances->id)]) ?>

                </td>
            </tr>
            <?php endforeach; ?>
        </table>
    <?php endif; ?>
    </div>
</div>
