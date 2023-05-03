<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Bookmarkurl'), ['action' => 'edit', $bookmarkurl->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Bookmarkurl'), ['action' => 'delete', $bookmarkurl->id], ['confirm' => __('Are you sure you want to delete # {0}?', $bookmarkurl->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Bookmarkurls'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Bookmarkurl'), ['action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Bookmarkgroups'), ['controller' => 'Bookmarkgroups', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Bookmarkgroup'), ['controller' => 'Bookmarkgroups', 'action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="bookmarkurls view large-9 medium-8 columns content">
    <h3><?= h($bookmarkurl->title) ?></h3>
    <table class="vertical-table">
        <tr>
            <th><?= __('Title') ?></th>
            <td><?= h($bookmarkurl->title) ?></td>
        </tr>
        <tr>
            <th><?= __('Id') ?></th>
            <td><?= $this->Number->format($bookmarkurl->id) ?></td>
        </tr>
    </table>
    <div class="row">
        <h4><?= __('Query String') ?></h4>
        <?= $this->Text->autoParagraph(h($bookmarkurl->query_string)); ?>
    </div>
    <div class="related">
        <h4><?= __('Related Bookmarkgroups') ?></h4>
        <?php if (!empty($bookmarkurl->bookmarkgroups)): ?>
        <table cellpadding="0" cellspacing="0">
            <tr>
                <th><?= __('Id') ?></th>
                <th><?= __('Title') ?></th>
                <th><?= __('Comment') ?></th>
                <th class="actions"><?= __('Actions') ?></th>
            </tr>
            <?php foreach ($bookmarkurl->bookmarkgroups as $bookmarkgroups): ?>
            <tr>
                <td><?= h($bookmarkgroups->id) ?></td>
                <td><?= h($bookmarkgroups->title) ?></td>
                <td><?= h($bookmarkgroups->comment) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['controller' => 'Bookmarkgroups', 'action' => 'view', $bookmarkgroups->id]) ?>

                    <?= $this->Html->link(__('Edit'), ['controller' => 'Bookmarkgroups', 'action' => 'edit', $bookmarkgroups->id]) ?>

                    <?= $this->Form->postLink(__('Delete'), ['controller' => 'Bookmarkgroups', 'action' => 'delete', $bookmarkgroups->id], ['confirm' => __('Are you sure you want to delete # {0}?', $bookmarkgroups->id)]) ?>

                </td>
            </tr>
            <?php endforeach; ?>
        </table>
    <?php endif; ?>
    </div>
</div>
