<span class="played ajax-button-form">
    <?= $this->Form->create(null, ['url' => ['controller' => 'SongPerformances', 'action' => 'addAjax']]) ?>
    <?= $this->Form->hidden('song_id', ['value' => $current_song->id]);    ?>
    <span class="performance-register-submit button">
        <?= $this->Form->button(__('Played'), ['type' => 'button', 'onclick' => 'SBK.CakeUI.form.ajaxify(this, SBK.CakeUI.ajaxcallback.register_performance);']) ?>
    </span>
    <?= $this->Form->end() ?>
</span>
