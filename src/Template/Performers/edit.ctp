<?php /* Template/Performers/edit.ctp */
    $controller_name = 'Performer';
    echo($this->element('standard_menu', ['controller_name' => $controller_name]) );
?>
<div class="performers form large-9 medium-8 columns content">
    <?= $this->Form->create($performer) ?>
    <fieldset>
        <legend><?= __('Edit Performer') ?></legend>
        <?php
            echo $this->Form->input('name');
            echo $this->Form->input('nickname');
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
