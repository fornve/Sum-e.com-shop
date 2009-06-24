<div class="post">
	<h2 class="title">Change password for administrator {$admin->username}</h2>

	<div class="post_content">
		<form method="post" action="/Admin/ChangePassword/{$admin->id}">
			<table>
				<tr>
					<th>Password</th>
					<td><input type="password" name="password" /></td>
				</tr>
				<tr>
					<th>Confirm Password</th>
					<td><input type="password" name="confirm_password" /></td>
				</tr>
			</table>
			<input type="submit" value="Change password!" />
		</form>
	</div>
</div>