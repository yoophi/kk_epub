<!--
<div class="row-fluid">
<div class="span2">
	<div class="sidebar-nav">
		<ul class="nav nav-list">
			<li><?php echo $this->Html->link(__('새로운 책'), array('action' => 'add')); ?></li>
		</ul>
	</div>
</div>

<div class="span10">
-->
<div class="books index">
	<div class="page-header">
	<h1><?php echo __('Books');?></h1>
	</div>
	<table cellpadding="0" cellspacing="0" class="table table-striped">
	<thead>
	<tr>
			<th><?php echo $this->Paginator->sort('id');?></th>
			<th><?php echo $this->Paginator->sort('subject');?></th>
			<th><?php echo $this->Paginator->sort('created');?></th>
	</tr>
	</thead>
	<tbody>
	<?php
	foreach ($books as $book): ?>
	
	<tr>
		<td><?php echo h($book['Book']['id']); ?>&nbsp;</td>
		<td><?php echo $this->Html->link($book['Book']['subject'], array('action' => 'view', 'id' => $book['Book']['id'])); ?>&nbsp;</td>
		<td><?php echo h($book['Book']['created']); ?>&nbsp;</td>
	</tr>
<?php endforeach; ?>
	</tbody>
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

	<!--
</div>

</div>
</div>
-->
