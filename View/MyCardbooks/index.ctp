<div class="books index">
    <div class="page-header">
        <h1><?php echo __('Cardbooks');?></h1>
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
        <?php foreach ($cardbooks as $cardbook): ?>
        <tr>
            <td><?php echo h($cardbook['Cardbook']['id']); ?>&nbsp;</td>
            <td><?php echo $this->Html->link($cardbook['Cardbook']['subject'], array('action' => 'view', 'id' => $cardbook['Cardbook']['id'])); ?>&nbsp;</td>
            <td><?php echo h($cardbook['Cardbook']['created']); ?>&nbsp;</td>
        </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <p>
        <?php
        echo $this->Paginator->counter(array(
            'format' => __('Page {:page} of {:pages}, showing {:current} records out of {:count} total, starting on record {:start}, ending on {:end}')
        ));
        ?>
    </p>

    <div class="paging">
        <?php
        echo $this->Paginator->prev('< ' . __('previous'), array(), null, array('class' => 'prev disabled'));
        echo $this->Paginator->numbers(array('separator' => ''));
        echo $this->Paginator->next(__('next') . ' >', array(), null, array('class' => 'next disabled'));
        ?>
    </div>

</div>
