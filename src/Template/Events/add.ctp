<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <?= $this->element('standard_menu') ?>
</nav>
<div class="events form large-9 medium-8 columns content">
    <?= $this->Form->create($event) ?>
    <fieldset>
        <legend><?= __('Add Event') ?></legend>
        <?php
            echo $this->Form->input('venue');
            echo $this->Form->input('timestamp');
            echo $this->Form->input('duration_hours');
            echo $this->Form->input('notes');
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
