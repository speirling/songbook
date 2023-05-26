<?php /* Template/Songs/add.php */ 
    $controller_name = 'Song';
    echo($this->element('standard_menu', ['controller_name' => $controller_name, 'delete_id' => $song->id]) );
?>

<div class="songs form large-9 medium-8 columns content">
    <?= $this->Form->create($song) ?>
    <fieldset>
        <legend><?= __('Add Song') ?></legend>
        <?php
            echo $this->Form->control('title');
            echo $this->Form->control('written_by');
            echo $this->Form->control('performed_by');
            echo $this->Form->control('base_key');
            echo $this->Form->control('content');
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
