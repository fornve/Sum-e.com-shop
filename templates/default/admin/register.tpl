<div>
	<div style="margin: 40px 0 60px 60px;">
	<h1>User Login</h1>

		<form action="/User/Register" method="post">
			<div>
				<label>Username</label>
				<br />
				<input type="text" name="username" id="username" class="text" maxlength="40" />
			</div>

			<div>
				<label>Password</label>
				<br />
				<input type="password" name="password" id="password" class="text" maxlength="40" />
			</div>

			<div>
				<label>Confirm Password</label>
				<br />
				<input type="password" name="confirm_password" id="confirm_password" class="text" maxlength="40" />
			</div>

			<div>
				<label>Email</label>
				<div class="quiet">Optional - only for password reset</div>
				<br />
				<input type="text" name="email" id="email" class="text" maxlength="120" />
			</div>

			<div>
				<input type="submit" value="Register" onclick="" />
			</div>
		</form>
	</div>
	<div style="clear: both"></div>
</div>
