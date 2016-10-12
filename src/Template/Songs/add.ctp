<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <?= $this->element('standard_menu') ?>
</nav>
<div class="songs form large-9 medium-8 columns content">
    <?= $this->Form->create($song) ?>
    <fieldset>
        <legend><?= __('Add Song') ?></legend>
        <?php
            echo $this->Form->input('title');
            echo $this->Form->input('written_by');
            echo $this->Form->input('performed_by');
            echo $this->Form->input('base_key');
            echo $this->Form->input('content');
            echo $this->Form->input('original_filename');
            echo $this->Form->input('meta_tags');
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
