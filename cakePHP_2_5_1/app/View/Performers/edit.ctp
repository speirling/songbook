<div class="performers form">
<?php echo $this->Form->create('Performer'); ?>
	<fieldset>
		<legend><?php echo __('Edit Performer'); ?></legend>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('name');
		echo $this->Form->input('nickname');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $this->Form->value('Performer.id')), array(), __('Are you sure you want to delete # %s?', $this->Form->value('Performer.id'))); ?></li>
		<li><?php echo $this->Html->link(__('List Performers'), array('action' => 'index')); ?></li>
		<li><?php echo $this->Html->link(__('List Song Instances'), array('controller' => 'song_instances', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Song Instance'), array('controller' => 'song_instances', 'action' => 'add')); ?> </li>
	</ul>
</div>
