<?php /* Template/PlaylistSets/index.ctp */  
    $controller_name = 'PlaylistSet';
    echo($this->element('standard_menu', ['controller_name' => $controller_name]) );
?>
<div class="playlistSets index large-9 medium-8 columns content">
    <h3><?= __('Playlist Sets') ?></h3>
    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th><?= $this->Paginator->sort('id') ?></th>
                <th><?= $this->Paginator->sort('set_id') ?></th>
                <th><?= $this->Paginator->sort('playlist_id') ?></th>
                <th><?= $this->Paginator->sort('order') ?></th>
                <th class="actions"><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($playlistSets as $playlistSet): ?>
            <tr>
                <td><?= $this->Number->format($playlistSet->id) ?></td>
                <td><?= $playlistSet->has('set') ? $this->Html->link($playlistSet->set->title, ['controller' => 'Sets', 'action' => 'view', $playlistSet->set->id]) : '' ?></td>
                <td><?= $playlistSet->has('playlist') ? $this->Html->link($playlistSet->playlist->title, ['controller' => 'Playlists', 'action' => 'view', $playlistSet->playlist->id]) : '' ?></td>
                <td><?= $this->Number->format($playlistSet->order) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['action' => 'view', $playlistSet->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['action' => 'edit', $playlistSet->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $playlistSet->id], ['confirm' => __('Are you sure you want to delete # {0}?', $playlistSet->id)]) ?>
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
