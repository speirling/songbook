<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Bookmarkgroup'), ['action' => 'edit', $bookmarkgroup->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Bookmarkgroup'), ['action' => 'delete', $bookmarkgroup->id], ['confirm' => __('Are you sure you want to delete # {0}?', $bookmarkgroup->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Bookmarkgroups'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Bookmarkgroup'), ['action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Bookmarkurls'), ['controller' => 'Bookmarkurls', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Bookmarkurl'), ['controller' => 'Bookmarkurls', 'action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="bookmarkgroups view large-9 medium-8 columns content">
    <h3><?= h($bookmarkgroup->title) ?></h3>
    <table class="vertical-table">
        <tr>
            <th><?= __('Title') ?></th>
            <td><?= h($bookmarkgroup->title) ?></td>
        </tr>
        <tr>
            <th><?= __('Id') ?></th>
            <td><?= $this->Number->format($bookmarkgroup->id) ?></td>
        </tr>
    </table>
    <div class="row">
        <h4><?= __('Comment') ?></h4>
        <?= $this->Text->autoParagraph(h($bookmarkgroup->comment)); ?>
    </div>
    <div class="related">
        <h4><?= __('Related Bookmarkurls') ?></h4>
        <?php if (!empty($bookmarkgroup->bookmarkurls)): ?>
        <table cellpadding="0" cellspacing="0">
            <tr>
                <th><?= __('Id') ?></th>
                <th><?= __('Title') ?></th>
                <th><?= __('Query String') ?></th>
                <th class="actions"><?= __('Actions') ?></th>
            </tr>
            <?php foreach ($bookmarkgroup->bookmarkurls as $bookmarkurls): ?>
            <tr>
                <td><?= h($bookmarkurls->id) ?></td>
                <td><?= h($bookmarkurls->title) ?></td>
                <td><?= h($bookmarkurls->query_string) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['controller' => 'Bookmarkurls', 'action' => 'view', $bookmarkurls->id]) ?>

                    <?= $this->Html->link(__('Edit'), ['controller' => 'Bookmarkurls', 'action' => 'edit', $bookmarkurls->id]) ?>

                    <?= $this->Form->postLink(__('Delete'), ['controller' => 'Bookmarkurls', 'action' => 'delete', $bookmarkurls->id], ['confirm' => __('Are you sure you want to delete # {0}?', $bookmarkurls->id)]) ?>

                </td>
            </tr>
            <?php endforeach; ?>
        </table>
    <?php endif; ?>
    </div>
</div>
