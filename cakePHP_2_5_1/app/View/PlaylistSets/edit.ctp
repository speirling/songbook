<div class="playlistSets form">
<?php echo $this->Form->create('PlaylistSet'); ?>
	<fieldset>
		<legend><?php echo __('Edit Playlist Set'); ?></legend>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('set_id');
		echo $this->Form->input('playlist_id');
		echo $this->Form->input('order');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $this->Form->value('PlaylistSet.id')), array(), __('Are you sure you want to delete # %s?', $this->Form->value('PlaylistSet.id'))); ?></li>
		<li><?php echo $this->Html->link(__('List Playlist Sets'), array('action' => 'index')); ?></li>
		<li><?php echo $this->Html->link(__('List Sets'), array('controller' => 'sets', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Set'), array('controller' => 'sets', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Playlists'), array('controller' => 'playlists', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Playlist'), array('controller' => 'playlists', 'action' => 'add')); ?> </li>
	</ul>
</div>
