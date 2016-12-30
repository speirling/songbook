<?php /* Template/SongTags/index.ctp */  
    $controller_name = 'SongTag';
    echo($this->element('standard_menu', ['controller_name' => $controller_name]) );
?>
<div class="songTags index large-9 medium-8 columns content">
    <h3><?= __('Song Tags') ?></h3>
    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th><?= $this->Paginator->sort('id') ?></th>
                <th><?= $this->Paginator->sort('song_id') ?></th>
                <th><?= $this->Paginator->sort('tag_id') ?></th>
                <th class="actions"><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($songTags as $songTag): ?>
            <tr>
                <td><?= $this->Number->format($songTag->id) ?></td>
                <td><?= $songTag->has('song') ? $this->Html->link($songTag->song->title, ['controller' => 'Songs', 'action' => 'view', $songTag->song->id]) : '' ?></td>
                <td><?= $songTag->has('tag') ? $this->Html->link($songTag->tag->title, ['controller' => 'Tags', 'action' => 'view', $songTag->tag->id]) : '' ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['action' => 'view', $songTag->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['action' => 'edit', $songTag->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $songTag->id], ['confirm' => __('Are you sure you want to delete # {0}?', $songTag->id)]) ?>
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
