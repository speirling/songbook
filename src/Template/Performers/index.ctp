<?php /* Template/Performers/index.ctp */  
    $controller_name = 'Performer';
    echo($this->element('standard_menu', ['controller_name' => $controller_name]) );
?>
<div class="performers index large-9 medium-8 columns content">
    <h3><?= __('Performers') ?></h3>
    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th><?= $this->Paginator->sort('id') ?></th>
                <th><?= $this->Paginator->sort('name') ?></th>
                <th><?= $this->Paginator->sort('nickname') ?></th>
                <th class="actions"><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($performers as $performer): ?>
            <tr>
                <td><?= $this->Number->format($performer->id) ?></td>
                <td><?= h($performer->name) ?></td>
                <td><?= h($performer->nickname) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['action' => 'view', $performer->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['action' => 'edit', $performer->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $performer->id], ['confirm' => __('Are you sure you want to delete # {0}?', $performer->id)]) ?>
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
