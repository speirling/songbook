<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $set->id],
                ['confirm' => __('Are you sure you want to delete # {0}?', $set->id)]
            )
        ?></li>
        <li><?= $this->Html->link(__('List Sets'), ['action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('List Performers'), ['controller' => 'Performers', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Performer'), ['controller' => 'Performers', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Set Songs'), ['controller' => 'SetSongs', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Set Song'), ['controller' => 'SetSongs', 'action' => 'add']) ?></li>
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
</div>
