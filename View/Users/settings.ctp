<div class="users form">
<?php echo $this->Form->create('User');?>
	<fieldset>
		<legend><?php echo __('설정'); ?></legend>
	<?php
        $checked = false;
        if (!empty($settings['User']['use_texteditor'])) {
            $checked = true;
        }
		echo $this->Form->input('use_texteditor', array('type' => 'checkbox', 'checked' => $checked));
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit'));?>
</div>
