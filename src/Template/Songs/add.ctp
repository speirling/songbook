<?php /* Template/Songs/add.php */ 
    $controller_name = 'Song';
    echo($this->element('standard_menu', ['controller_name' => $controller_name, 'delete_id' => $song->id]) );
?>

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
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
