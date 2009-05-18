<div class="post basket">
    <h2 class="title">Administrators</h2>

    <table>

        <thead>
            <tr class="header">
                <th>ID</th>
                <th>Username</th>
                <th>Last login time</th>
                <th>Last login ip</th>
                <th style="width: 60px;">Action</th>
            </tr>
        </thead>

        <tbody>
        {foreach from=$admins item=admin name=adminloop}
            <tr class="item">
                <td>{$admin->id}</td>
                <td>{$admin->username}</td>
				<td>{$admin->last_login_time}</td>
                <td>{$admin->last_login_ip}</td>

				<td>
					<a href="/Admin/ChangePassword/{$admin->id}" title="Change password"><img src="http://sunforum.co.uk/resources/icons/silk/application_edit.png" alt="Edit" /></a>
					<a href="/Admin/Delete/{$admin->id}" onclick="return confirm('Do you really want to delete admin: {$admin->username}?')" alt="Delete"><img src="http://sunforum.co.uk/resources/icons/silk/application_delete.png" title="Delete" /></a>
				</td>
            </tr>
        {/foreach}
        </tbody>
    </table>

	<h2 class="title">New admin</h2>
	<form method="post" action="/Admin/Edit/">
		<table>
			<tr>
				<th>Username</th>
				<td><input type="text" name="username" /></td>
			</tr>
			<tr>
				<th>Password</th>
				<td><input type="text" name="password" /></td>
			</tr>
		</table>
		<input type="submit" value="Create admin!" />
	</form>
</div>
