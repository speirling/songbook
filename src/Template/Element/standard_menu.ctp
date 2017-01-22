<nav class="large-3 medium-3 columns" id="actions-sidebar">
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
        <li class="heading"><?= __('Main Menu') ?></li>
        <li><?= $this->Html->link(__('New Song'), ['controller' => 'Songs', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('New Event'), ['controller' => 'Events', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('Songs'), ['controller' => 'Songs', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('Playlists'), ['controller' => 'Playlists', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('Performers'), ['controller' => 'Performers', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('Events'), ['controller' => 'Events', 'action' => 'index']) ?></li>
        <?php  if(isset($controller_name)){ ?>
        <li class="heading"><?= __($controller_name . ' Actions') ?></li>
        <li><?= $this->Html->link(__('New ' . $controller_name), ['action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List ' . $controller_name . 's'), ['controller' => $controller_name.'s', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Song Performance'), ['controller' =>  $controller_name.'s', 'action' => 'add']) ?></li>
        <?php  }?>
    </ul>
</nav>