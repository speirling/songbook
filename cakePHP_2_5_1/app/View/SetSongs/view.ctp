<div class="setSongs view">
<h2><?php echo __('Set Song'); ?></h2>
	<dl>
		<dt><?php echo __('Id'); ?></dt>
		<dd>
			<?php echo h($setSong['SetSong']['id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Song'); ?></dt>
		<dd>
			<?php echo $this->Html->link($setSong['Song']['title'], array('controller' => 'songs', 'action' => 'view', $setSong['Song']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Set'); ?></dt>
		<dd>
			<?php echo $this->Html->link($setSong['Set']['title'], array('controller' => 'sets', 'action' => 'view', $setSong['Set']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Order'); ?></dt>
		<dd>
			<?php echo h($setSong['SetSong']['order']); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Set Song'), array('action' => 'edit', $setSong['SetSong']['id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete Set Song'), array('action' => 'delete', $setSong['SetSong']['id']), array(), __('Are you sure you want to delete # %s?', $setSong['SetSong']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('List Set Songs'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Set Song'), array('action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Songs'), array('controller' => 'songs', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Song'), array('controller' => 'songs', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Sets'), array('controller' => 'sets', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Set'), array('controller' => 'sets', 'action' => 'add')); ?> </li>
	</ul>
</div>
