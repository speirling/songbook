<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('New Bookmarkgroup'), ['action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Bookmarkurls'), ['controller' => 'Bookmarkurls', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Bookmarkurl'), ['controller' => 'Bookmarkurls', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="bookmarkgroups index large-9 medium-8 columns content">
    <h3><?= __('Bookmarkgroups') ?></h3>
    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th><?= $this->Paginator->sort('id') ?></th>
                <th><?= $this->Paginator->sort('title') ?></th>
                <th class="actions"><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($bookmarkgroups as $bookmarkgroup): ?>
            <tr>
                <td><?= $this->Number->format($bookmarkgroup->id) ?></td>
                <td><?= h($bookmarkgroup->title) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['action' => 'view', $bookmarkgroup->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['action' => 'edit', $bookmarkgroup->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $bookmarkgroup->id], ['confirm' => __('Are you sure you want to delete # {0}?', $bookmarkgroup->id)]) ?>
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
