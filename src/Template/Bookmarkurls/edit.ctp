<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $bookmarkurl->id],
                ['confirm' => __('Are you sure you want to delete # {0}?', $bookmarkurl->id)]
            )
        ?></li>
        <li><?= $this->Html->link(__('List Bookmarkurls'), ['action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('List Bookmarkgroups'), ['controller' => 'Bookmarkgroups', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Bookmarkgroup'), ['controller' => 'Bookmarkgroups', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="bookmarkurls form large-9 medium-8 columns content">
    <?= $this->Form->create($bookmarkurl) ?>
    <fieldset>
        <legend><?= __('Edit Bookmarkurl') ?></legend>
        <?php
            echo $this->Form->input('title');
            echo $this->Form->input('query_string');
            echo $this->Form->input('bookmarkgroups._ids', ['options' => $bookmarkgroups]);
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
