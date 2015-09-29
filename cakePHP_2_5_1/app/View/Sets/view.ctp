<div class="sets view">
<h2><?php echo __('Set'); ?></h2>
	<dl>
		<dt><?php echo __('Id'); ?></dt>
		<dd>
			<?php echo h($set['Set']['id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Title'); ?></dt>
		<dd>
			<?php echo h($set['Set']['title']); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Set'), array('action' => 'edit', $set['Set']['id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete Set'), array('action' => 'delete', $set['Set']['id']), array(), __('Are you sure you want to delete # %s?', $set['Set']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('List Sets'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Set'), array('action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Set Songs'), array('controller' => 'set_songs', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Set Song'), array('controller' => 'set_songs', 'action' => 'add')); ?> </li>
	</ul>
</div>
<div class="related">
	<h3><?php echo __('Related Set Songs'); ?></h3>
	<?php if (!empty($set['SetSong'])): ?>
	<table cellpadding = "0" cellspacing = "0">
	<tr>
		<th><?php echo __('Id'); ?></th>
		<th><?php echo __('Song Id'); ?></th>
		<th><?php echo __('Set Id'); ?></th>
		<th><?php echo __('Order'); ?></th>
		<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	<?php foreach ($set['SetSong'] as $setSong): ?>
		<tr>
			<td><?php echo $setSong['id']; ?></td>
			<td><?php echo $setSong['song_id']; ?></td>
			<td><?php echo $setSong['set_id']; ?></td>
			<td><?php echo $setSong['order']; ?></td>
			<td class="actions">
				<?php echo $this->Html->link(__('View'), array('controller' => 'set_songs', 'action' => 'view', $setSong['id'])); ?>
				<?php echo $this->Html->link(__('Edit'), array('controller' => 'set_songs', 'action' => 'edit', $setSong['id'])); ?>
				<?php echo $this->Form->postLink(__('Delete'), array('controller' => 'set_songs', 'action' => 'delete', $setSong['id']), array(), __('Are you sure you want to delete # %s?', $setSong['id'])); ?>
			</td>
		</tr>
	<?php endforeach; ?>
	</table>
<?php endif; ?>

	<div class="actions">
		<ul>
			<li><?php echo $this->Html->link(__('New Set Song'), array('controller' => 'set_songs', 'action' => 'add')); ?> </li>
		</ul>
	</div>
</div>
