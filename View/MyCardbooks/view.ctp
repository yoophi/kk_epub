<div class="row-fluid">
  <?php pr($cardbook); ?>
</div>

<div class="actions">
    <h3><?php echo __('Actions'); ?></h3>
    <ul>
        <li><?php echo $this->Html->link(__('Edit Cardbook'), array('action' => 'edit', 'id' => $cardbook['Cardbook.id'])); ?> </li>
        <li><?php echo $this->Html->link(__('Spine'), array('action' => 'spine', 'id' => $cardbook['Cardbook.id'])); ?> </li>
        <li><?php echo $this->Form->postLink(__('Delete Cardbook'), array('action' => 'destroy', 'id' => $cardbook['Cardbook.id']), null, __('Are you sure you want to delete # %s?', $cardbook['Cardbook.id'])); ?> </li>
        <li><?php echo $this->Html->link(__('List Cardbooks'), array('action' => 'index')); ?> </li>
    </ul>
</div>
