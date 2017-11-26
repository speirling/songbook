<span class="view ajax-button-form">
    <?= $this->Form->create(null, ['type' => 'get', 'target'=>'_blank', 'url' => ['controller'=>'Songs', 'action' => 'view', $current_song->id]]) ?>
    <?= $this->Form->hidden('key', ['value' => $primary_key]);    ?>
    <span class="view-song-submit button">
        <?= $this->Form->button(__('View'), ['type' => 'submit']) ?>
    </span>
    <?= $this->Form->end() ?>
</span>
