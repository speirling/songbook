<div class="performers view">
<h2><?php echo __('Performer'); ?></h2>
	<dl>
		<dt><?php echo __('Id'); ?></dt>
		<dd>
			<?php echo h($performer['Performer']['id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Name'); ?></dt>
		<dd>
			<?php echo h($performer['Performer']['name']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Nickname'); ?></dt>
		<dd>
			<?php echo h($performer['Performer']['nickname']); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Performer'), array('action' => 'edit', $performer['Performer']['id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete Performer'), array('action' => 'delete', $performer['Performer']['id']), array(), __('Are you sure you want to delete # %s?', $performer['Performer']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('List Performers'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Performer'), array('action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Song Instances'), array('controller' => 'song_instances', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Song Instance'), array('controller' => 'song_instances', 'action' => 'add')); ?> </li>
	</ul>
</div>
<div class="related">
	<h3><?php echo __('Related Song Instances'); ?></h3>
	<?php if (!empty($performer['SongInstance'])): ?>
	<table cellpadding = "0" cellspacing = "0">
	<tr>
		<th><?php echo __('Id'); ?></th>
		<th><?php echo __('Song Id'); ?></th>
		<th><?php echo __('Performer Id'); ?></th>
		<th><?php echo __('Key'); ?></th>
		<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	<?php foreach ($performer['SongInstance'] as $songInstance): ?>
		<tr>
			<td><?php echo $songInstance['id']; ?></td>
			<td><?php echo $songInstance['song_id']; ?></td>
			<td><?php echo $songInstance['performer_id']; ?></td>
			<td><?php echo $songInstance['key']; ?></td>
			<td class="actions">
				<?php echo $this->Html->link(__('View'), array('controller' => 'song_instances', 'action' => 'view', $songInstance['id'])); ?>
				<?php echo $this->Html->link(__('Edit'), array('controller' => 'song_instances', 'action' => 'edit', $songInstance['id'])); ?>
				<?php echo $this->Form->postLink(__('Delete'), array('controller' => 'song_instances', 'action' => 'delete', $songInstance['id']), array(), __('Are you sure you want to delete # %s?', $songInstance['id'])); ?>
			</td>
		</tr>
	<?php endforeach; ?>
	</table>
<?php endif; ?>

	<div class="actions">
		<ul>
			<li><?php echo $this->Html->link(__('New Song Instance'), array('controller' => 'song_instances', 'action' => 'add')); ?> </li>
		</ul>
	</div>
</div>
