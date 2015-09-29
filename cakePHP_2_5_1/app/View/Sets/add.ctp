<div class="sets form">
<?php echo $this->Form->create('Set'); ?>
	<fieldset>
		<legend><?php echo __('Add Set'); ?></legend>
	<?php
		echo $this->Form->input('title');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Html->link(__('List Sets'), array('action' => 'index')); ?></li>
		<li><?php echo $this->Html->link(__('List Set Songs'), array('controller' => 'set_songs', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Set Song'), array('controller' => 'set_songs', 'action' => 'add')); ?> </li>
	</ul>
</div>
