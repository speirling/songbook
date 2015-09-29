<div class="sets index">
	<h2><?php echo __('Sets'); ?></h2>
	<table cellpadding="0" cellspacing="0">
	<tr>
			<th><?php echo $this->Paginator->sort('id'); ?></th>
			<th><?php echo $this->Paginator->sort('title'); ?></th>
			<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	<?php foreach ($sets as $set): ?>
	<tr>
		<td><?php echo h($set['Set']['id']); ?>&nbsp;</td>
		<td><?php echo h($set['Set']['title']); ?>&nbsp;</td>
		<td class="actions">
			<?php echo $this->Html->link(__('View'), array('action' => 'view', $set['Set']['id'])); ?>
			<?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $set['Set']['id'])); ?>
			<?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $set['Set']['id']), array(), __('Are you sure you want to delete # %s?', $set['Set']['id'])); ?>
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
		<li><?php echo $this->Html->link(__('New Set'), array('action' => 'add')); ?></li>
		<li><?php echo $this->Html->link(__('List Set Songs'), array('controller' => 'set_songs', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Set Song'), array('controller' => 'set_songs', 'action' => 'add')); ?> </li>
	</ul>
</div>
