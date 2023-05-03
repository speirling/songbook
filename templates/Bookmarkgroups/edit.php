<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $bookmarkgroup->id],
                ['confirm' => __('Are you sure you want to delete # {0}?', $bookmarkgroup->id)]
            )
        ?></li>
        <li><?= $this->Html->link(__('List Bookmarkgroups'), ['action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('List Bookmarkurls'), ['controller' => 'Bookmarkurls', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Bookmarkurl'), ['controller' => 'Bookmarkurls', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="bookmarkgroups form large-9 medium-8 columns content">
    <?= $this->Form->create($bookmarkgroup) ?>
    <fieldset>
        <legend><?= __('Edit Bookmarkgroup') ?></legend>
        <?php
            echo $this->Form->control('title');
            echo $this->Form->control('comment');
            echo $this->Form->control('bookmarkurls._ids', ['options' => $bookmarkurls]);
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
