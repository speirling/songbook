<div class="songs form">
<?php echo $this->Form->create('Song'); ?>
	<fieldset>
		<legend><?php echo __('Edit Song'); ?></legend>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('title');
		echo $this->Form->input('written_by');
		echo $this->Form->input('performed_by');
		echo $this->Form->input('base_key');
		echo $this->Form->input('content');
		echo $this->Form->input('original_filename');
		echo $this->Form->input('meta_tags');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $this->Form->value('Song.id')), array(), __('Are you sure you want to delete # %s?', $this->Form->value('Song.id'))); ?></li>
		<li><?php echo $this->Html->link(__('List Songs'), array('action' => 'index')); ?></li>
		<li><?php echo $this->Html->link(__('List Song Instances'), array('controller' => 'song_instances', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Song Instance'), array('controller' => 'song_instances', 'action' => 'add')); ?> </li>
	</ul>
</div>
