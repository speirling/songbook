<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('New Bookmarkurl'), ['action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Bookmarkgroups'), ['controller' => 'Bookmarkgroups', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Bookmarkgroup'), ['controller' => 'Bookmarkgroups', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="bookmarkurls index large-9 medium-8 columns content">
    <h3><?= __('Bookmarkurls') ?></h3>
    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th><?= $this->Paginator->sort('id') ?></th>
                <th><?= $this->Paginator->sort('title') ?></th>
                <th class="actions"><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($bookmarkurls as $bookmarkurl): ?>
            <tr>
                <td><?= $this->Number->format($bookmarkurl->id) ?></td>
                <td><?= h($bookmarkurl->title) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['action' => 'view', $bookmarkurl->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['action' => 'edit', $bookmarkurl->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $bookmarkurl->id], ['confirm' => __('Are you sure you want to delete # {0}?', $bookmarkurl->id)]) ?>
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
