<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $songPerformance->id],
                ['confirm' => __('Are you sure you want to delete # {0}?', $songPerformance->id)]
            )
        ?></li>
        <li><?= $this->Html->link(__('List Song Performances'), ['action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('List Songs'), ['controller' => 'Songs', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Song'), ['controller' => 'Songs', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="songPerformances form large-9 medium-8 columns content">
    <?= $this->Form->create($songPerformance) ?>
    <fieldset>
        <legend><?= __('Edit Song Performance') ?></legend>
        <?php
            echo $this->Form->control('song_id', ['options' => $songs]);
            echo $this->Form->control('timestamp');
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
