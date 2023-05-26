<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('New Bookmarkurls Bookmarkgroup'), ['action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Bookmarkgroups'), ['controller' => 'Bookmarkgroups', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Bookmarkgroup'), ['controller' => 'Bookmarkgroups', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Bookmarkurls'), ['controller' => 'Bookmarkurls', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Bookmarkurl'), ['controller' => 'Bookmarkurls', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="bookmarkurlsBookmarkgroups index large-9 medium-8 columns content">
    <h3><?= __('Bookmarkurls Bookmarkgroups') ?></h3>
    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th><?= $this->Paginator->sort('id') ?></th>
                <th><?= $this->Paginator->sort('bookmarkgroup_id') ?></th>
                <th><?= $this->Paginator->sort('bookmarkurl_id') ?></th>
                <th class="actions"><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($bookmarkurlsBookmarkgroups as $bookmarkurlsBookmarkgroup): ?>
            <tr>
                <td><?= $this->Number->format($bookmarkurlsBookmarkgroup->id) ?></td>
                <td><?= $bookmarkurlsBookmarkgroup->has('bookmarkgroup') ? $this->Html->link($bookmarkurlsBookmarkgroup->bookmarkgroup->title, ['controller' => 'Bookmarkgroups', 'action' => 'view', $bookmarkurlsBookmarkgroup->bookmarkgroup->id]) : '' ?></td>
                <td><?= $bookmarkurlsBookmarkgroup->has('bookmarkurl') ? $this->Html->link($bookmarkurlsBookmarkgroup->bookmarkurl->title, ['controller' => 'Bookmarkurls', 'action' => 'view', $bookmarkurlsBookmarkgroup->bookmarkurl->id]) : '' ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['action' => 'view', $bookmarkurlsBookmarkgroup->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['action' => 'edit', $bookmarkurlsBookmarkgroup->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $bookmarkurlsBookmarkgroup->id], ['confirm' => __('Are you sure you want to delete # {0}?', $bookmarkurlsBookmarkgroup->id)]) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <div class="paginator">
        <ul class="pagination">
            <?= $this->Paginator->prev('< ' . __('previous')) ?>
            <?= $this->Paginator->numbers() ?>
            <?= $this->Paginator->next(__('next') . ' >') ?>
        </ul>
        <p><?= $this->Paginator->counter() ?></p>
    </div>
</div>
