<div class="playlistSets view">
<h2><?php echo __('Playlist Set'); ?></h2>
	<dl>
		<dt><?php echo __('Id'); ?></dt>
		<dd>
			<?php echo h($playlistSet['PlaylistSet']['id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Set'); ?></dt>
		<dd>
			<?php echo $this->Html->link($playlistSet['Set']['title'], array('controller' => 'sets', 'action' => 'view', $playlistSet['Set']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Playlist'); ?></dt>
		<dd>
			<?php echo $this->Html->link($playlistSet['Playlist']['title'], array('controller' => 'playlists', 'action' => 'view', $playlistSet['Playlist']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Order'); ?></dt>
		<dd>
			<?php echo h($playlistSet['PlaylistSet']['order']); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Playlist Set'), array('action' => 'edit', $playlistSet['PlaylistSet']['id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete Playlist Set'), array('action' => 'delete', $playlistSet['PlaylistSet']['id']), array(), __('Are you sure you want to delete # %s?', $playlistSet['PlaylistSet']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('List Playlist Sets'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Playlist Set'), array('action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Sets'), array('controller' => 'sets', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Set'), array('controller' => 'sets', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Playlists'), array('controller' => 'playlists', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Playlist'), array('controller' => 'playlists', 'action' => 'add')); ?> </li>
	</ul>
</div>
