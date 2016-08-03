<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $set->id],
                ['confirm' => __('Are you sure you want to delete # {0}?', $set->id)]
            )
        ?></li>
        <li><?= $this->Html->link(__('List Songs'), ['controller' => 'Songs', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Song'), ['controller' => 'Songs', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Playlists'), ['controller' => 'Playlists', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Playlist'), ['controller' => 'Playlists', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Sets'), ['controller' => 'Sets', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Set'), ['controller' => 'Sets', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Set Songs'), ['controller' => 'SetSongs', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Set Song'), ['controller' => 'SetSongs', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Performers'), ['controller' => 'Performers', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Performer'), ['controller' => 'Performers', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="sets form large-9 medium-8 columns content">
    <?= $this->Form->create($set) ?>
    <fieldset>
        <legend><?= __('Edit Set') ?></legend>
        <?php
            echo $this->Form->input('title');
            echo $this->Form->input('performer_id', ['options' => $performers]);
            echo $this->Form->input('Comment');
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
    <table cellpadding="0" cellspacing="0" class="set-songs">
        <?php foreach ($set->set_songs as $setSongs): ?>
        <tr>
            <td class="set-song-id"><?= h($setSongs->id) ?></td>
            <td class="song-id"><?= h($setSongs->song->id) ?></td>
            <td class="set-song-title"><?= h($setSongs->song->title) . ' <span class="performed-by">(' .  $setSongs->song->performed_by . ')</span>'; ?></td>
            <td class="actions">
                <?= $this->Form->postLink(__('remove from set'), ['controller' => 'SetSongs', 'action' => 'delete', $setSongs->id], ['confirm' => __('Are you sure you want to remove Set-Song relationship # {0}?', $setSongs->id)]) ?>

            </td>
        </tr>
        <?php endforeach; ?>
    </table>
</div>
</div>
