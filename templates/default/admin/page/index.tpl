<div class="post basket">
	<h2 class="title">Shop pages</h2>

	<p><a href="/PageAdmin/Edit/" class="ui-state-default ui-corner-all"><img src="/resources/icons/mini/icon_wand.gif" alt="New page" />New page</a></p>

	<div class="post_content">
		<table class="admin_table">

			<thead>
				<tr class="header">
					<th>ID</th>
					<th>Title</th>
					<th>Type</th>
					<th></th>
				</tr>
			</thead>

			<tbody>
			{foreach from=$pages item=page}
				<tr class="item">
					<td class="center">{$page->id}</td>
					<td>{$page->title}</td>
					<td>{$page->type}</td>
					<td>
						<a href="/PageAdmin/Edit/{$page->id}">Edit</a>
						<span> / </span>
						<a href="/Page/View/{$page->id}/{$page->title}">View</a>
						<span> / </span>
						<a href="/PageAdmin/Delete/{$page->id}" onclick="return confirm('Do you really want to delete page: {$page->title}?')">Delete</a>
					</td>
				</tr>
			{/foreach}
			</tbody>
		</table>

		<p><sup>*)</sup><span>To refresh <a href="/PageAdmin/FlushCache/">flush cache</a></span></p>
	</div>
</div>
