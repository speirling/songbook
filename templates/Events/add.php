<?php /* Template/Events/index.ctp */ 
    $controller_name = 'Event';
    echo($this->element('standard_menu', ['controller_name' => $controller_name]) );
?>
<div class="events form large-9 medium-8 columns content">
    <?= $this->Form->create($event) ?>
    <fieldset>
        <legend><?= __('Add Event') ?></legend>
        <?php
            echo $this->Form->control('venue');
            echo $this->Form->control('timestamp');
            echo $this->Form->control('duration_hours');
            echo $this->Form->control('notes');
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
