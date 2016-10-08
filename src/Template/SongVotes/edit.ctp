<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $songVote->id],
                ['confirm' => __('Are you sure you want to delete # {0}?', $songVote->id)]
            )
        ?></li>
        <li><?= $this->Html->link(__('List Song Votes'), ['action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('List Songs'), ['controller' => 'Songs', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Song'), ['controller' => 'Songs', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="songVotes form large-9 medium-8 columns content">
    <?= $this->Form->create($songVote) ?>
    <fieldset>
        <legend><?= __('Edit Song Vote') ?></legend>
        <?php
            echo $this->Form->input('song_id', ['options' => $songs]);
            echo $this->Form->input('timestamp');
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
