<?php /* Template/SetSongs/index.ctp */  
    $controller_name = 'SetSong';
    echo($this->element('standard_menu', ['controller_name' => $controller_name]) );
?>
<div class="setSongs index large-9 medium-8 columns content">
    <h3><?= __('Set Songs') ?></h3>
    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th><?= $this->Paginator->sort('id') ?></th>
                <th><?= $this->Paginator->sort('set_id') ?></th>
                <th><?= $this->Paginator->sort('song_id') ?></th>
                <th><?= $this->Paginator->sort('order') ?></th>
                <th><?= $this->Paginator->sort('performer_id') ?></th>
                <th><?= $this->Paginator->sort('key') ?></th>
                <th><?= $this->Paginator->sort('capo') ?></th>
                <th class="actions"><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($setSongs as $setSong): ?>
            <tr>
                <td><?= $this->Number->format($setSong->id) ?></td>
                <td><?= $setSong->has('set') ? $this->Html->link($setSong->set->title, ['controller' => 'Sets', 'action' => 'view', $setSong->set->id]) : '' ?></td>
                <td><?= $setSong->has('song') ? $this->Html->link($setSong->song->title, ['controller' => 'Songs', 'action' => 'view', $setSong->song->id]) : '' ?></td>
                <td><?= $this->Number->format($setSong->order) ?></td>
                <td><?= $setSong->has('performer') ? $this->Html->link($setSong->performer->name, ['controller' => 'Performers', 'action' => 'view', $setSong->performer->id]) : '' ?></td>
                <td><?= h($setSong->key) ?></td>
                <td><?= $this->Number->format($setSong->capo) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['action' => 'view', $setSong->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['action' => 'edit', $setSong->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $setSong->id], ['confirm' => __('Are you sure you want to delete # {0}?', $setSong->id)]) ?>
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
