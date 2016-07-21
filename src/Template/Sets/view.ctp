<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Set'), ['action' => 'edit', $set->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Set'), ['action' => 'delete', $set->id], ['confirm' => __('Are you sure you want to delete # {0}?', $set->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Sets'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Set'), ['action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Performers'), ['controller' => 'Performers', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Performer'), ['controller' => 'Performers', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Set Songs'), ['controller' => 'SetSongs', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Set Song'), ['controller' => 'SetSongs', 'action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="sets view large-9 medium-8 columns content">
    <h3><?= h($set->title) ?></h3>
    <table class="vertical-table">
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
    <div class="row">
        <h4><?= __('Comment') ?></h4>
        <?= $this->Text->autoParagraph(h($set->Comment)); ?>
    </div>
    <div class="related">
        <h4><?= __('Related Set Songs') ?></h4>
        <?php if (!empty($set->set_songs)): ?>
        <table cellpadding="0" cellspacing="0">
            <tr>
                <th><?= __('Id') ?></th>
                <th><?= __('Set Id') ?></th>
                <th><?= __('Song Id') ?></th>
                <th><?= __('Order') ?></th>
                <th><?= __('Performer Id') ?></th>
                <th><?= __('Key') ?></th>
                <th><?= __('Capo') ?></th>
                <th class="actions"><?= __('Actions') ?></th>
            </tr>
            <?php foreach ($set->set_songs as $setSongs): ?>
            <tr>
                <td><?= h($setSongs->id) ?></td>
                <td><?= h($setSongs->set_id) ?></td>
                <td><?= h($setSongs->song_id) ?></td>
                <td><?= h($setSongs->order) ?></td>
                <td><?= h($setSongs->performer_id) ?></td>
                <td><?= h($setSongs->key) ?></td>
                <td><?= h($setSongs->capo) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['controller' => 'SetSongs', 'action' => 'view', $setSongs->id]) ?>

                    <?= $this->Html->link(__('Edit'), ['controller' => 'SetSongs', 'action' => 'edit', $setSongs->id]) ?>

                    <?= $this->Form->postLink(__('Delete'), ['controller' => 'SetSongs', 'action' => 'delete', $setSongs->id], ['confirm' => __('Are you sure you want to delete # {0}?', $setSongs->id)]) ?>

                </td>
            </tr>
            <?php endforeach; ?>
        </table>
    <?php endif; ?>
    </div>
</div>
