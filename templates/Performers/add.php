<?php /* Template/Performers/add.ctp */
    $controller_name = 'Performer';
    echo($this->element('standard_menu', ['controller_name' => $controller_name]) );
?>
<div class="performers form large-9 medium-8 columns content">
    <?= $this->Form->create($performer) ?>
    <fieldset>
        <legend><?= __('Add Performer') ?></legend>
        <?php
            echo $this->Form->control('name');
            echo $this->Form->control('nickname');
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
