<div class="songs index">
	<h2><?php echo __('Songs'); ?></h2>
	<table cellpadding="0" cellspacing="0">
	<tr>
			<th class="id"><?php echo $this->Paginator->sort('id'); ?></th>
			<th class="title"><?php echo $this->Paginator->sort('title'); ?></th>
			<th class="written_by"><?php echo $this->Paginator->sort('written_by'); ?></th>
			<th class="performed_by"><?php echo $this->Paginator->sort('performed_by'); ?></th>
			<th class="base_key"><?php echo $this->Paginator->sort('base_key'); ?></th>
			<th class="content"><?php echo $this->Paginator->sort('content'); ?></th>
			<th class="original_filename"><?php echo $this->Paginator->sort('original_filename'); ?></th>
			<th class="meta_tags"><?php echo $this->Paginator->sort('meta_tags'); ?></th>
			<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	<?php foreach ($songs as $song): ?>
	<tr>
		<td class="id"><?php echo h($song['Song']['id']); ?>&nbsp;</td>
		<td class="title"><?php echo h($song['Song']['title']); ?>&nbsp;</td>
		<td class="written_by"><?php echo h($song['Song']['written_by']); ?>&nbsp;</td>
		<td class="performed_by"><?php echo h($song['Song']['performed_by']); ?>&nbsp;</td>
		<td class="base_key"><?php echo h($song['Song']['base_key']); ?>&nbsp;</td>
		<td class="content"><?php echo h($song['Song']['content']); ?>&nbsp;</td>
		<td class="original_filename"><?php echo h($song['Song']['original_filename']); ?>&nbsp;</td>
		<td class="meta_tags"><?php echo h($song['Song']['meta_tags']); ?>&nbsp;</td>
		<td class="actions">
			<?php echo $this->Html->link(__('View'), array('action' => 'view', $song['Song']['id'])); ?>
			<?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $song['Song']['id'])); ?>
			<?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $song['Song']['id']), array(), __('Are you sure you want to delete # %s?', $song['Song']['id'])); ?>
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
		<li><?php echo $this->Html->link(__('New Song'), array('action' => 'add')); ?></li>
		<li><?php echo $this->Html->link(__('List Song Instances'), array('controller' => 'song_instances', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Song Instance'), array('controller' => 'song_instances', 'action' => 'add')); ?> </li>
	</ul>
</div>
