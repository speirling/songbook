<div class="performers index">
	<h2><?php echo __('Performers'); ?></h2>
	<table cellpadding="0" cellspacing="0">
	<tr>
			<th><?php echo $this->Paginator->sort('id'); ?></th>
			<th><?php echo $this->Paginator->sort('name'); ?></th>
			<th><?php echo $this->Paginator->sort('nickname'); ?></th>
			<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	<?php foreach ($performers as $performer): ?>
	<tr>
		<td><?php echo h($performer['Performer']['id']); ?>&nbsp;</td>
		<td><?php echo h($performer['Performer']['name']); ?>&nbsp;</td>
		<td><?php echo h($performer['Performer']['nickname']); ?>&nbsp;</td>
		<td class="actions">
			<?php echo $this->Html->link(__('View'), array('action' => 'view', $performer['Performer']['id'])); ?>
			<?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $performer['Performer']['id'])); ?>
			<?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $performer['Performer']['id']), array(), __('Are you sure you want to delete # %s?', $performer['Performer']['id'])); ?>
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
		<li><?php echo $this->Html->link(__('New Performer'), array('action' => 'add')); ?></li>
		<li><?php echo $this->Html->link(__('List Song Instances'), array('controller' => 'song_instances', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Song Instance'), array('controller' => 'song_instances', 'action' => 'add')); ?> </li>
	</ul>
</div>
