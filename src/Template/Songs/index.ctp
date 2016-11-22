<?php /* Template/Songs/index.php */ ?>

<nav class="large-2 medium-2 columns" id="actions-sidebar">
    <?= $this->element('standard_menu') ?>
</nav>
<div class="songs index large-10 medium-10 columns content">

    <h3><?= __('Songs') ?></h3>
    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th class="id"><?= $this->Paginator->sort('id') ?></th>
                <th class="title"><?= $this->Paginator->sort('title') ?></th>
                <th class="written-by-performed-by"><span class="written-by"><?= $this->Paginator->sort('written_by') ?></span><br>
                <span class="performed-by"><?= $this->Paginator->sort('performed_by') ?></span></th>
                <th class="actions"><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($songs as $song): ?>
            <tr>
                <td class="id"><?= $this->Number->format($song->id) ?></td>
                <td class="title"><?= h($song->title) ?></td>
                <td class="written-by-performed-by"><span class="written-by"><?= h($song->written_by) ?></span><br>
                <span class="performed-by"><?= h($song->performed_by) ?></span></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['action' => 'view', $song->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['action' => 'edit', $song->id]) ?>
                        <span class="button vote"><?= 
                                    $this->Html->link(__(
                                        'vote'
                                    ), [
                                        'controller' => 'SongVotes', 
                                        'action' => 'addret', 
                                        $song->id,
                                        'songs', 'search', $search_string
                                    ]) ?></span>  
                                <span class="button performance"><?= 
                                    $this->Html->link(__(
                                        'played'
                                    ), [
                                        'controller' => 'SongPerformances', 
                                        'action' => 'addret', 
                                        $song->id,
                                        'songs', 'search', $search_string
                                    ]) ?></span>  
                    <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $song->id], ['confirm' => __('Are you sure you want to delete # {0}?', $song->id)]) ?>
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
