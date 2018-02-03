<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('List Bookmarkgroups'), ['action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('List Bookmarkurls'), ['controller' => 'Bookmarkurls', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Bookmarkurl'), ['controller' => 'Bookmarkurls', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="bookmarkgroups form large-9 medium-8 columns content">
    <?= $this->Form->create($bookmarkgroup) ?>
    <fieldset>
        <legend><?= __('Add Bookmarkgroup') ?></legend>
        <?php
            echo $this->Form->input('title');
            echo $this->Form->input('comment');
            echo $this->Form->input('bookmarkurls._ids', ['options' => $bookmarkurls]);
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
