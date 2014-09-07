<form class="form-horizontal" action="<?php echo $BASE.'/user/password'; ?>" method="POST">
  <fieldset>
    <div id="legend">
      <legend class="">Change Password</legend>
    </div>
    <div class="control-group">
      <!-- Current Password -->
      <label class="control-label"  for="current_password">Current Password</label>
      <div class="controls">
        <input type="password" id="current_password" name="current_password" placeholder="" class="input-xlarge" required>
        <p class="help-block">To change your password you have to provide current password</p>
      </div>
    </div>
 
    <div class="control-group">
      <!-- Password-->
      <label class="control-label" for="new_password">New Password</label>
      <div class="controls">
        <input type="password" id="new_password" name="new_password" placeholder="" class="input-xlarge" required>
        <p class="help-block">Password should be at least 4 characters</p>
      </div>
    </div>
 
    <div class="control-group">
      <!-- Password -->
      <label class="control-label"  for="password_confirm">New Password (Confirm)</label>
      <div class="controls">
        <input type="password" id="password_confirm" name="password_confirm" placeholder="" class="input-xlarge" required>
        <p class="help-block">Please confirm password</p>
      </div>
    </div>
	<input type="hidden" name="password_reset" value="password_reset" />
    <div class="control-group">
      <!-- Button -->
      <div class="controls">
        <button class="btn btn-success">Submit</button>
      </div>
    </div>
  </fieldset>
</form>
