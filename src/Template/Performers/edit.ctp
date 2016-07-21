<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $performer->id],
                ['confirm' => __('Are you sure you want to delete # {0}?', $performer->id)]
            )
        ?></li>
        <li><?= $this->Html->link(__('List Performers'), ['action' => 'index']) ?></li>
    </ul>
</nav>
<div class="performers form large-9 medium-8 columns content">
    <?= $this->Form->create($performer) ?>
    <fieldset>
        <legend><?= __('Edit Performer') ?></legend>
        <?php
            echo $this->Form->input('name');
            echo $this->Form->input('nickname');
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
