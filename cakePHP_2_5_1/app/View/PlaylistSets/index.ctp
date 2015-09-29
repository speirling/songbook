<div class="playlistSets index">
	<h2><?php echo __('Playlist Sets'); ?></h2>
	<table cellpadding="0" cellspacing="0">
	<tr>
			<th><?php echo $this->Paginator->sort('id'); ?></th>
			<th><?php echo $this->Paginator->sort('set_id'); ?></th>
			<th><?php echo $this->Paginator->sort('playlist_id'); ?></th>
			<th><?php echo $this->Paginator->sort('order'); ?></th>
			<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	<?php foreach ($playlistSets as $playlistSet): ?>
	<tr>
		<td><?php echo h($playlistSet['PlaylistSet']['id']); ?>&nbsp;</td>
		<td>
			<?php echo $this->Html->link($playlistSet['Set']['title'], array('controller' => 'sets', 'action' => 'view', $playlistSet['Set']['id'])); ?>
		</td>
		<td>
			<?php echo $this->Html->link($playlistSet['Playlist']['title'], array('controller' => 'playlists', 'action' => 'view', $playlistSet['Playlist']['id'])); ?>
		</td>
		<td><?php echo h($playlistSet['PlaylistSet']['order']); ?>&nbsp;</td>
		<td class="actions">
			<?php echo $this->Html->link(__('View'), array('action' => 'view', $playlistSet['PlaylistSet']['id'])); ?>
			<?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $playlistSet['PlaylistSet']['id'])); ?>
			<?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $playlistSet['PlaylistSet']['id']), array(), __('Are you sure you want to delete # %s?', $playlistSet['PlaylistSet']['id'])); ?>
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
		<li><?php echo $this->Html->link(__('New Playlist Set'), array('action' => 'add')); ?></li>
		<li><?php echo $this->Html->link(__('List Sets'), array('controller' => 'sets', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Set'), array('controller' => 'sets', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Playlists'), array('controller' => 'playlists', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Playlist'), array('controller' => 'playlists', 'action' => 'add')); ?> </li>
	</ul>
</div>
