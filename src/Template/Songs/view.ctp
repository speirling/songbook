<nav class="large-2 medium-2 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Song'), ['action' => 'edit', $song->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Song'), ['action' => 'delete', $song->id], ['confirm' => __('Are you sure you want to delete # {0}?', $song->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Songs'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Song'), ['action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Song Instances'), ['controller' => 'SongInstances', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Song Instance'), ['controller' => 'SongInstances', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Song Tags'), ['controller' => 'SongTags', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Song Tag'), ['controller' => 'SongTags', 'action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="songs view large-10 medium-10 columns content">
    <h3><?= h($song->title) ?></h3>
    <table class="vertical-table">
        <tr class="title">
            <th><?= __('Title') ?></th>
            <td><?= h($song->title) ?></td>
        </tr>
        <tr class="written-by">
            <th><?= __('Written By') ?></th>
            <td><?= h($song->written_by) ?></td>
        </tr>
        <tr class="performed-by">
            <th><?= __('Performed By') ?></th>
            <td><?= h($song->performed_by) ?></td>
        </tr>
        <tr class="base-key">
            <th><?= __('Base Key') ?></th>
            <td><?= h($song->base_key) ?></td>
        </tr>
        <tr class="original-filename">
            <th><?= __('Original Filename') ?></th>
            <td><?= h($song->original_filename) ?></td>
        </tr>
        <tr class="id">
            <th><?= __('Id') ?></th>
            <td><?= $this->Number->format($song->id) ?></td>
        </tr>
    </table>
    <div class="row">
        <h4><?= __('Content') ?></h4>
        <?= $this->Text->autoParagraph(h($song->content)); ?>
    </div>
    <div class="row">
        <h4><?= __('Meta Tags') ?></h4>
        <?= $this->Text->autoParagraph(h($song->meta_tags)); ?>
    </div>
    <div class="related">
        <h4><?= __('Related Song Instances') ?></h4>
        <?php if (!empty($song->song_instances)): ?>
        <table cellpadding="0" cellspacing="0">
            <tr>
                <th><?= __('Id') ?></th>
                <th><?= __('Song Id') ?></th>
                <th><?= __('Performer Id') ?></th>
                <th><?= __('Key') ?></th>
                <th class="actions"><?= __('Actions') ?></th>
            </tr>
            <?php foreach ($song->song_instances as $songInstances): ?>
            <tr>
                <td><?= h($songInstances->id) ?></td>
                <td><?= h($songInstances->song_id) ?></td>
                <td><?= h($songInstances->performer_id) ?></td>
                <td><?= h($songInstances->key) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['controller' => 'SongInstances', 'action' => 'view', $songInstances->id]) ?>

                    <?= $this->Html->link(__('Edit'), ['controller' => 'SongInstances', 'action' => 'edit', $songInstances->id]) ?>

                    <?= $this->Form->postLink(__('Delete'), ['controller' => 'SongInstances', 'action' => 'delete', $songInstances->id], ['confirm' => __('Are you sure you want to delete # {0}?', $songInstances->id)]) ?>

                </td>
            </tr>
            <?php endforeach; ?>
        </table>
    <?php endif; ?>
    </div>
    <div class="related">
        <h4><?= __('Related Song Tags') ?></h4>
        <?php if (!empty($song->song_tags)): ?>
        <table cellpadding="0" cellspacing="0">
            <tr>
                <th><?= __('Id') ?></th>
                <th><?= __('Tag Id') ?></th>
                <th><?= __('Song Id') ?></th>
                <th class="actions"><?= __('Actions') ?></th>
            </tr>
            <?php foreach ($song->song_tags as $songTags): ?>
            <tr>
                <td><?= h($songTags->id) ?></td>
                <td><?= h($songTags->tag_id) ?></td>
                <td><?= h($songTags->song_id) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['controller' => 'SongTags', 'action' => 'view', $songTags->id]) ?>

                    <?= $this->Html->link(__('Edit'), ['controller' => 'SongTags', 'action' => 'edit', $songTags->id]) ?>

                    <?= $this->Form->postLink(__('Delete'), ['controller' => 'SongTags', 'action' => 'delete', $songTags->id], ['confirm' => __('Are you sure you want to delete # {0}?', $songTags->id)]) ?>

                </td>
            </tr>
            <?php endforeach; ?>
        </table>
    <?php endif; ?>
    </div>
</div>
