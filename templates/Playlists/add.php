<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <?= $this->element('standard_menu') ?>
</nav>
<div class="playlists form large-9 medium-8 columns content">
    <?= $this->Form->create($playlist) ?>
    <fieldset>
        <legend><?= __('Add Playlist') ?></legend>
        <?php
            echo $this->Form->control('title');
            echo $this->Form->control('performer_id');
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
