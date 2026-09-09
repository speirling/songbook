<?php
/**
 * @var \App\View\AppView $this
 * @var iterable<\App\Model\Entity\Customlist> $customlists
 */
?>
<div class="customlists index content">
    <?= $this->Html->link(__('New Customlist'), ['action' => 'add'], ['class' => 'button float-right']) ?>
    <h3><?= __('Customlists') ?></h3>
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th><?= $this->Paginator->sort('id') ?></th>
                    <th><?= $this->Paginator->sort('title') ?></th>
                    <th class="actions"><?= __('Actions') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($customlists as $customlist): ?>
                <tr>
                    <td><?= $this->Number->format($customlist->id) ?></td>
                    <td><?= h($customlist->title) ?></td>
                    <td class="actions">
                        <?= $this->Html->link(__('View'), ['action' => 'view', $customlist->id]) ?>
                        <?= $this->Html->link(__('Edit'), ['action' => 'edit', $customlist->id]) ?>
                        <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $customlist->id], ['confirm' => __('Are you sure you want to delete # {0}?', $customlist->id)]) ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <div class="paginator">
        <ul class="pagination">
            <?= $this->Paginator->first('<< ' . __('first')) ?>
            <?= $this->Paginator->prev('< ' . __('previous')) ?>
            <?= $this->Paginator->numbers() ?>
            <?= $this->Paginator->next(__('next') . ' >') ?>
            <?= $this->Paginator->last(__('last') . ' >>') ?>
        </ul>
        <p><?= $this->Paginator->counter(__('Page {{page}} of {{pages}}, showing {{current}} record(s) out of {{count}} total')) ?></p>
    </div>
</div>
