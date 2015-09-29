<div class="songTags view">
<h2><?php echo __('Song Tag'); ?></h2>
	<dl>
		<dt><?php echo __('Id'); ?></dt>
		<dd>
			<?php echo h($songTag['SongTag']['id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Tag'); ?></dt>
		<dd>
			<?php echo $this->Html->link($songTag['Tag']['name'], array('controller' => 'tags', 'action' => 'view', $songTag['Tag']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Song'); ?></dt>
		<dd>
			<?php echo $this->Html->link($songTag['Song']['title'], array('controller' => 'songs', 'action' => 'view', $songTag['Song']['id'])); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Song Tag'), array('action' => 'edit', $songTag['SongTag']['id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete Song Tag'), array('action' => 'delete', $songTag['SongTag']['id']), array(), __('Are you sure you want to delete # %s?', $songTag['SongTag']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('List Song Tags'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Song Tag'), array('action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Tags'), array('controller' => 'tags', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Tag'), array('controller' => 'tags', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Songs'), array('controller' => 'songs', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Song'), array('controller' => 'songs', 'action' => 'add')); ?> </li>
	</ul>
</div>
