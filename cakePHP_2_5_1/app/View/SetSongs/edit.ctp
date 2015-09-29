<div class="setSongs form">
<?php echo $this->Form->create('SetSong'); ?>
	<fieldset>
		<legend><?php echo __('Edit Set Song'); ?></legend>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('song_id');
		echo $this->Form->input('set_id');
		echo $this->Form->input('order');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $this->Form->value('SetSong.id')), array(), __('Are you sure you want to delete # %s?', $this->Form->value('SetSong.id'))); ?></li>
		<li><?php echo $this->Html->link(__('List Set Songs'), array('action' => 'index')); ?></li>
		<li><?php echo $this->Html->link(__('List Songs'), array('controller' => 'songs', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Song'), array('controller' => 'songs', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Sets'), array('controller' => 'sets', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Set'), array('controller' => 'sets', 'action' => 'add')); ?> </li>
	</ul>
</div>
