<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $setSong->id],
                ['confirm' => __('Are you sure you want to delete # {0}?', $setSong->id)]
            )
        ?></li>
        <li><?= $this->Html->link(__('List Set Songs'), ['action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('List Songs'), ['controller' => 'Songs', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Song'), ['controller' => 'Songs', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Sets'), ['controller' => 'Sets', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Set'), ['controller' => 'Sets', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="setSongs form large-9 medium-8 columns content">
    <?= $this->Form->create($setSong) ?>
    <fieldset>
        <legend><?= __('Edit Set Song') ?></legend>
        <?php
            echo $this->Form->input('song_id', ['options' => $songs]);
            echo $this->Form->input('set_id', ['options' => $sets]);
            echo $this->Form->input('order');
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
