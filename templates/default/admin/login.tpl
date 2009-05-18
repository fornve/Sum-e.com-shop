<div>
	<div style="margin: 40px 0 60px 60px;">
	<h1>Admin Login</h1>

		<form action="/Admin/Login" method="post">
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
				<input type="submit" value="Login" onclick="return loginFormValidate();" />
			</div>
		</form>
	</div>
	<div style="clear: both"></div>
</div>
