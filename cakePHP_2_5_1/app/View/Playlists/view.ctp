<div class="playlists view">
<h2><?php echo __('Playlist'); ?></h2>
	<dl>
		<dt><?php echo __('Id'); ?></dt>
		<dd>
			<?php echo h($playlist['Playlist']['id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Title'); ?></dt>
		<dd>
			<?php echo h($playlist['Playlist']['title']); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Playlist'), array('action' => 'edit', $playlist['Playlist']['id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete Playlist'), array('action' => 'delete', $playlist['Playlist']['id']), array(), __('Are you sure you want to delete # %s?', $playlist['Playlist']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('List Playlists'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Playlist'), array('action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Playlist Sets'), array('controller' => 'playlist_sets', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Playlist Set'), array('controller' => 'playlist_sets', 'action' => 'add')); ?> </li>
	</ul>
</div>
<div class="related">
	<h3><?php echo __('Related Playlist Sets'); ?></h3>
	<?php if (!empty($playlist['PlaylistSet'])): ?>
	<table cellpadding = "0" cellspacing = "0">
	<tr>
		<th><?php echo __('Id'); ?></th>
		<th><?php echo __('Set Id'); ?></th>
		<th><?php echo __('Playlist Id'); ?></th>
		<th><?php echo __('Order'); ?></th>
		<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	<?php foreach ($playlist['PlaylistSet'] as $playlistSet): ?>
		<tr>
			<td><?php echo $playlistSet['id']; ?></td>
			<td><?php echo $playlistSet['set_id']; ?></td>
			<td><?php echo $playlistSet['playlist_id']; ?></td>
			<td><?php echo $playlistSet['order']; ?></td>
			<td class="actions">
				<?php echo $this->Html->link(__('View'), array('controller' => 'playlist_sets', 'action' => 'view', $playlistSet['id'])); ?>
				<?php echo $this->Html->link(__('Edit'), array('controller' => 'playlist_sets', 'action' => 'edit', $playlistSet['id'])); ?>
				<?php echo $this->Form->postLink(__('Delete'), array('controller' => 'playlist_sets', 'action' => 'delete', $playlistSet['id']), array(), __('Are you sure you want to delete # %s?', $playlistSet['id'])); ?>
			</td>
		</tr>
	<?php endforeach; ?>
	</table>
<?php endif; ?>

	<div class="actions">
		<ul>
			<li><?php echo $this->Html->link(__('New Playlist Set'), array('controller' => 'playlist_sets', 'action' => 'add')); ?> </li>
		</ul>
	</div>
</div>
