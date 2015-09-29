<div class="songs view">
<h2><?php echo __('Song'); ?></h2>
	<dl>
		<dt><?php echo __('Id'); ?></dt>
		<dd>
			<?php echo h($song['Song']['id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Title'); ?></dt>
		<dd>
			<?php echo h($song['Song']['title']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Written By'); ?></dt>
		<dd>
			<?php echo h($song['Song']['written_by']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Performed By'); ?></dt>
		<dd>
			<?php echo h($song['Song']['performed_by']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Base Key'); ?></dt>
		<dd>
			<?php echo h($song['Song']['base_key']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Content'); ?></dt>
		<dd>
			<?php echo h($song['Song']['content']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Original Filename'); ?></dt>
		<dd>
			<?php echo h($song['Song']['original_filename']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Meta Tags'); ?></dt>
		<dd>
			<?php echo h($song['Song']['meta_tags']); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Song'), array('action' => 'edit', $song['Song']['id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete Song'), array('action' => 'delete', $song['Song']['id']), array(), __('Are you sure you want to delete # %s?', $song['Song']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('List Songs'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Song'), array('action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Song Instances'), array('controller' => 'song_instances', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Song Instance'), array('controller' => 'song_instances', 'action' => 'add')); ?> </li>
	</ul>
</div>
<div class="related">
	<h3><?php echo __('Related Song Instances'); ?></h3>
	<?php if (!empty($song['SongInstance'])): ?>
	<table cellpadding = "0" cellspacing = "0">
	<tr>
		<th><?php echo __('Id'); ?></th>
		<th><?php echo __('Song Id'); ?></th>
		<th><?php echo __('Performer Id'); ?></th>
		<th><?php echo __('Key'); ?></th>
		<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	<?php foreach ($song['SongInstance'] as $songInstance): ?>
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
