<span class="vote ajax-button-form">
    <?= $this->Form->create(null, ['url' => ['controller' => 'SongVotes', 'action' => 'addAjax']]) ?>
    <?= $this->Form->hidden('song_id', ['value' => $current_song->id]);    ?>
    <span class="vote-register-submit button">
        <?= $this->Form->button(__('Vote'), ['type' => 'button', 'onclick' => 'SBK.CakeUI.form.ajaxify(this, SBK.CakeUI.ajaxcallback.register_vote);']) ?>
    </span>
    <?= $this->Form->end() ?>
</span>  
