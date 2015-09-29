<div class="songInstances view">
<h2><?php echo __('Song Instance'); ?></h2>
	<dl>
		<dt><?php echo __('Id'); ?></dt>
		<dd>
			<?php echo h($songInstance['SongInstance']['id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Song'); ?></dt>
		<dd>
			<?php echo $this->Html->link($songInstance['Song']['title'], array('controller' => 'songs', 'action' => 'view', $songInstance['Song']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Performer'); ?></dt>
		<dd>
			<?php echo $this->Html->link($songInstance['Performer']['name'], array('controller' => 'performers', 'action' => 'view', $songInstance['Performer']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Key'); ?></dt>
		<dd>
			<?php echo h($songInstance['SongInstance']['key']); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Song Instance'), array('action' => 'edit', $songInstance['SongInstance']['id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete Song Instance'), array('action' => 'delete', $songInstance['SongInstance']['id']), array(), __('Are you sure you want to delete # %s?', $songInstance['SongInstance']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('List Song Instances'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Song Instance'), array('action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Songs'), array('controller' => 'songs', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Song'), array('controller' => 'songs', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Performers'), array('controller' => 'performers', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Performer'), array('controller' => 'performers', 'action' => 'add')); ?> </li>
	</ul>
</div>
