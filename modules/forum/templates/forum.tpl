
<a href="/Forum/Post/{$forum->id}" title="New post">New Post</a>

<table class="forumtable">
	<tr>
		<th>Topics</th>
		<th>Replies</th>
		<th>Author</th>
		<th>Views</th>
		<th>Last post</th>
		<th></th>
	</tr>

	{foreach from=$forum->subjects item=subject}
	{assign var=last_reply value=$post->LastReply()}
	<tr>
		<td>
			<strong><a href="/Forum/Post/{$post->id}/{$post->subject}">{$post->subject}</a></strong>
		</td>
		<td class="center">{math equation="x - y" x=$post->ReplyCount() y=1}</td>
		<td class="center"><a href="/User/Profile/{$post->user->id}/{$post->user->username}">{$post->user->username}</a></td>
		<td class="center">{$subject->views}</td>
		<td class="center">
			{if $last_reply}
				{$last_post->created|date_format:'%Y-%m-%d %H:%m'}

				<br />
				<a href="/User/Profile/{$last_reply->user->id}/{$last_reply->user->username}">{$last_reply->user->username}</a>
			{/if}
		</td>
		<td>
			{if $user && $user->HasRole('admin')}
				<a href="/Forum/DeleteThread/{$subject->id}">Delete</a>
			{/if}
		</td>
	</tr>
	{/foreach}

</table>

<a href="/Forum/Post/{$forum->id}" title="New post">New Post</a>
