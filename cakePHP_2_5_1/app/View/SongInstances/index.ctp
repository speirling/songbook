<div class="songInstances index">
	<h2><?php echo __('Song Instances'); ?></h2>
	<table cellpadding="0" cellspacing="0">
	<tr>
			<th><?php echo $this->Paginator->sort('id'); ?></th>
			<th><?php echo $this->Paginator->sort('song_id'); ?></th>
			<th><?php echo $this->Paginator->sort('performer_id'); ?></th>
			<th><?php echo $this->Paginator->sort('key'); ?></th>
			<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	<?php foreach ($songInstances as $songInstance): ?>
	<tr>
		<td><?php echo h($songInstance['SongInstance']['id']); ?>&nbsp;</td>
		<td>
			<?php echo $this->Html->link($songInstance['Song']['title'], array('controller' => 'songs', 'action' => 'view', $songInstance['Song']['id'])); ?>
		</td>
		<td>
			<?php echo $this->Html->link($songInstance['Performer']['name'], array('controller' => 'performers', 'action' => 'view', $songInstance['Performer']['id'])); ?>
		</td>
		<td><?php echo h($songInstance['SongInstance']['key']); ?>&nbsp;</td>
		<td class="actions">
			<?php echo $this->Html->link(__('View'), array('action' => 'view', $songInstance['SongInstance']['id'])); ?>
			<?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $songInstance['SongInstance']['id'])); ?>
			<?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $songInstance['SongInstance']['id']), array(), __('Are you sure you want to delete # %s?', $songInstance['SongInstance']['id'])); ?>
		</td>
	</tr>
<?php endforeach; ?>
	</table>
	<p>
	<?php
	echo $this->Paginator->counter(array(
	'format' => __('Page {:page} of {:pages}, showing {:current} records out of {:count} total, starting on record {:start}, ending on {:end}')
	));
	?>	</p>
	<div class="paging">
	<?php
		echo $this->Paginator->prev('< ' . __('previous'), array(), null, array('class' => 'prev disabled'));
		echo $this->Paginator->numbers(array('separator' => ''));
		echo $this->Paginator->next(__('next') . ' >', array(), null, array('class' => 'next disabled'));
	?>
	</div>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('New Song Instance'), array('action' => 'add')); ?></li>
		<li><?php echo $this->Html->link(__('List Songs'), array('controller' => 'songs', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Song'), array('controller' => 'songs', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Performers'), array('controller' => 'performers', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Performer'), array('controller' => 'performers', 'action' => 'add')); ?> </li>
	</ul>
</div>
