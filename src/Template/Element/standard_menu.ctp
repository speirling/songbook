    <?= $this->Form->create(null, [
        'url' => ['controller' => 'Songs', 'action' => 'index']
    ]) ?>
    <fieldset>
        <?php
            echo $this->Form->input('Search', ['Label'=>'Find a song']);
        ?>
    </fieldset>
    <?= $this->Form->end() ?>
    <ul class="side-nav">
        <?= $this->Form->button(__('Submit')) ?>
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('New Song'), ['controller' => 'Songs', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Playlists'), ['controller' => 'Playlists', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Playlist'), ['controller' => 'Playlists', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Performers'), ['controller' => 'Performers', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Performer'), ['controller' => 'Performers', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Events'), ['controller' => 'Events', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Event'), ['controller' => 'Events', 'action' => 'add']) ?></li>
    </ul>