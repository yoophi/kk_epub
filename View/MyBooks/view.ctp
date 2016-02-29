<div class="row-fluid">
  <?php pr($book); ?>
</div>

<div class="actions">
    <h3><?php echo __('Actions'); ?></h3>
    <ul>
        <li><?php echo $this->Html->link(__('Edit Book'), array('action' => 'edit', 'id' => $book['Book.id'])); ?> </li>
        <li><?php echo $this->Html->link(__('Spine'), array('action' => 'spine', 'id' => $book['Book.id'])); ?> </li>
        <li><?php echo $this->Form->postLink(__('Delete Book'), array('action' => 'destroy', 'id' => $book['Book.id']), null, __('Are you sure you want to delete # %s?', $book['Book.id'])); ?> </li>
        <li><?php echo $this->Html->link(__('List Books'), array('action' => 'index')); ?> </li>
    </ul>
</div>
