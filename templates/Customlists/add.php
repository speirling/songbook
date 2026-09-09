<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Customlist $customlist
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Html->link(__('List Customlists'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column-responsive column-80">
        <div class="customlists form content">
            <?= $this->Form->create($customlist) ?>
            <fieldset>
                <legend><?= __('Add Customlist') ?></legend>
                <?php
                    echo $this->Form->control('title');
                    echo $this->Form->control('comment');
                    echo $this->Form->control('url');
                ?>
            </fieldset>
            <?= $this->Form->button(__('Submit')) ?>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>
