<div class="songInstances form">
<?php echo $this->Form->create('SongInstance'); ?>
	<fieldset>
		<legend><?php echo __('Add Song Instance'); ?></legend>
	<?php
		echo $this->Form->input('song_id');
		echo $this->Form->input('performer_id');
		echo $this->Form->input('key');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Html->link(__('List Song Instances'), array('action' => 'index')); ?></li>
		<li><?php echo $this->Html->link(__('List Songs'), array('controller' => 'songs', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Song'), array('controller' => 'songs', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Performers'), array('controller' => 'performers', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Performer'), array('controller' => 'performers', 'action' => 'add')); ?> </li>
	</ul>
</div>
