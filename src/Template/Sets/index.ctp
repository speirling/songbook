<?php /* Template/Sets/index.ctp */  
    $controller_name = 'Set';
    echo($this->element('standard_menu', ['controller_name' => $controller_name]) );
?>
<div class="sets index large-9 medium-8 columns content">
    <h3><?= __('Sets') ?></h3>
    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th><?= $this->Paginator->sort('id') ?></th>
                <th><?= $this->Paginator->sort('title') ?></th>
                <th><?= $this->Paginator->sort('performer_id') ?></th>
                <th class="actions"><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($sets as $set): ?>
            <tr>
                <td><?= $this->Number->format($set->id) ?></td>
                <td><?= h($set->title) ?></td>
                <td><?= $set->has('performer') ? $this->Html->link($set->performer->name, ['controller' => 'Performers', 'action' => 'view', $set->performer->id]) : '' ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['action' => 'view', $set->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['action' => 'edit', $set->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $set->id], ['confirm' => __('Are you sure you want to delete # {0}?', $set->id)]) ?>
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
