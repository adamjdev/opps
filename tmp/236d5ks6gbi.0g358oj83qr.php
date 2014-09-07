<div class="main">
	<form method="post" action="<?php echo $BASE; ?>/login" class="login">
		<h5>Login</h5>
		<?php if (isset($message)): ?>
		<p class="message"><?php echo $message; ?></p>
		<?php endif; ?>
		<p>
			<label for="user_id"><small>User ID</small></label><br />
			<input id="mobile" name="mobile" type="text" <?php echo isset($POST['mobile'])?('value="'.$POST['mobile'].'"'):''; ?> />
		</p>
		<p>
			<label for="password"><small>Password</small></label><br />
			<input id="password" name="password" type="password" />
		</p>
		<p>
			<input type="submit" />
		</p>
	</form>
</div>
