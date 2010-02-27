<div class="post">
	<h2 class="title">Forum</h2>

	<div class="post_content">
	<table class="forumtable">
		<tr>
			<th>Forum</th>
			<th>Posts</th>
			<th>Last post</th>
		</tr>
		{foreach from=$forums item=forum}
		{assign var=last_post value=$forum->LastPost()}
			<tr>
				<td><strong><a href="/Forum/View/{$forum->id}/{$forum->name}">{$forum->name}</strong></a>{if $forum->description}<br />{$forum->description}{/if}</td>
				<td class="center">{$forum->posts}</td>
				<td class="center">
					{if $last_post}
						{$last_post->posted|date_format:'%Y-%m-%d %H:%m'} <a href="/User/Profile/{$last_post->user->id}/{$last_post->user->username}">{$last_post->user->username}</a>
					{/if}
				</td>
			</tr>
		{/foreach}
		{if $user && $user->HasRole('admin')}
			<tr>
				<td>
					<form action="/ForumAdmin/Edit" method="post">
						<div>
							<label>Forum Name</label>
							<br />
							<input type="text" name="forum_name" class="text" />
							<br /><br />
							<label>Forum Description</label>
							<br />
							<input type="text" name="forum_description" class="text" />
							<br />
							<input type="submit" value="Create" />
						</div>
					</form>
				</td>
			</tr>
		{/if}
	   	</table>
	</div>
</div>
