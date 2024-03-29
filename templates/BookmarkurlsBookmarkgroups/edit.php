<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $bookmarkurlsBookmarkgroup->id],
                ['confirm' => __('Are you sure you want to delete # {0}?', $bookmarkurlsBookmarkgroup->id)]
            )
        ?></li>
        <li><?= $this->Html->link(__('List Bookmarkurls Bookmarkgroups'), ['action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('List Bookmarkgroups'), ['controller' => 'Bookmarkgroups', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Bookmarkgroup'), ['controller' => 'Bookmarkgroups', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Bookmarkurls'), ['controller' => 'Bookmarkurls', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Bookmarkurl'), ['controller' => 'Bookmarkurls', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="bookmarkurlsBookmarkgroups form large-9 medium-8 columns content">
    <?= $this->Form->create($bookmarkurlsBookmarkgroup) ?>
    <fieldset>
        <legend><?= __('Edit Bookmarkurls Bookmarkgroup') ?></legend>
        <?php
            echo $this->Form->control('bookmarkgroup_id', ['options' => $bookmarkgroups]);
            echo $this->Form->control('bookmarkurl_id', ['options' => $bookmarkurls]);
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
