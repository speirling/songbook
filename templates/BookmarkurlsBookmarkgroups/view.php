<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Bookmarkurls Bookmarkgroup'), ['action' => 'edit', $bookmarkurlsBookmarkgroup->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Bookmarkurls Bookmarkgroup'), ['action' => 'delete', $bookmarkurlsBookmarkgroup->id], ['confirm' => __('Are you sure you want to delete # {0}?', $bookmarkurlsBookmarkgroup->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Bookmarkurls Bookmarkgroups'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Bookmarkurls Bookmarkgroup'), ['action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Bookmarkgroups'), ['controller' => 'Bookmarkgroups', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Bookmarkgroup'), ['controller' => 'Bookmarkgroups', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Bookmarkurls'), ['controller' => 'Bookmarkurls', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Bookmarkurl'), ['controller' => 'Bookmarkurls', 'action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="bookmarkurlsBookmarkgroups view large-9 medium-8 columns content">
    <h3><?= h($bookmarkurlsBookmarkgroup->id) ?></h3>
    <table class="vertical-table">
        <tr>
            <th><?= __('Bookmarkgroup') ?></th>
            <td><?= $bookmarkurlsBookmarkgroup->has('bookmarkgroup') ? $this->Html->link($bookmarkurlsBookmarkgroup->bookmarkgroup->title, ['controller' => 'Bookmarkgroups', 'action' => 'view', $bookmarkurlsBookmarkgroup->bookmarkgroup->id]) : '' ?></td>
        </tr>
        <tr>
            <th><?= __('Bookmarkurl') ?></th>
            <td><?= $bookmarkurlsBookmarkgroup->has('bookmarkurl') ? $this->Html->link($bookmarkurlsBookmarkgroup->bookmarkurl->title, ['controller' => 'Bookmarkurls', 'action' => 'view', $bookmarkurlsBookmarkgroup->bookmarkurl->id]) : '' ?></td>
        </tr>
        <tr>
            <th><?= __('Id') ?></th>
            <td><?= $this->Number->format($bookmarkurlsBookmarkgroup->id) ?></td>
        </tr>
    </table>
</div>
