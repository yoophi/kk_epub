<?php
echo $this->Session->flash();
echo $this->Session->flash('auth');
?>
<div class="users form">
<?php echo $this->Form->create('User', array('action' => 'login', 'class' => 'form-horizontal'));?>
	<legend><?php echo __('관리자 로그인'); ?></legend>
	<div class="control-group">
    <label class="control-label" for="inputEmail">Email</label>
    <div class="controls">
		<?php echo $this->Form->text('username', array('id' => 'inputEmail', 'placeholder' => 'Email')); ?>
    </div>
  </div>
  <div class="control-group">
    <label class="control-label" for="inputPassword">Password</label>
    <div class="controls">
		<?php echo $this->Form->password('password', array('id' => 'inputPassword', 'placeholder' => 'Password')); ?>
    </div>
  </div>
  <div class="control-group">
    <div class="controls">
      <label class="checkbox">
        <input type="checkbox"> Remember me
      </label>
      <button type="submit" class="btn">Sign in</button>
    </div>
  </div>
</div>
