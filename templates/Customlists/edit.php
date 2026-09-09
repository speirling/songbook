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
            <?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $customlist->id],
                ['confirm' => __('Are you sure you want to delete # {0}?', $customlist->id), 'class' => 'side-nav-item']
            ) ?>
            <?= $this->Html->link(__('List Customlists'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column-responsive column-80">
        <div class="customlists form content">
       <?php  
       //debug($customlist);
/**
 * $customlist looks like this:
 
       object(App\Model\Entity\Customlist) id:0 {
           'id' => (int) 1 
           'title' => 'Test list 20251007' 
           'comment' => 'A list randomly put together to try to figure out the processes needed to implement a custom UI' 
           'url' => 'c[]=1154&c[]=38&c[]=973&c[]=44&c[]=45' 
           '[new]' => false 
           '[accessible]' => [ ] 
           '[dirty]' => [ ] 
           '[original]' => [ ] 
           '[virtual]' => [ ] 
           '[hasErrors]' => false 
           '[errors]' => [ ] 
           '[invalid]' => [ ] 
           '[repository]' => 'Customlists'
       
 */             
       ?>
            <?= $this->Form->create($customlist) ?>
            <fieldset>
                <legend><?= __('Edit Customlist') ?></legend>
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
