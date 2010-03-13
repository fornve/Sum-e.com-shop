<h2 class="alt">Forum: {$forum->name}</h2>

<h1 class="alt">{$subject->subject}</h1>

<table class="forumtable">
	<tr>
		<th width="100px;">Author</th>
		<th>Message</th>
	</tr>

	{foreach from=$posts item=post_item}
	<tr>
		<td><a href="#">{$post_item->user->username}</a></td>
		<td>
			<div class="quiet">
				Posted on {$post_item->created|date_format:'%Y-%m-%d %H:%m'}
				{if $user && ( $user->id==$post_item->user->id || $user->HasRole('admin'))}<a href="/Forum/DeletePost/{$post_item->id}">Delete post</a>{/if}
			</div>
			<hr />
			{$post_item->text|nl2br}
		</td>
	</tr>
	{/foreach}

	<tr>
		<td valign="top">
			<strong>{if $post->id}Reply:{else}New post{/if}</strong>
			<div class="quiet">Html tags allowed: 'a', 'pre', 'strong', 'code', 'quote'</div>
		</td>
		<td>
			{if $user}
			<form action="/Forum/Post/{$forum->id}/{$post->id}/{$post->subject}" method="post">
				<div>
					{if !$post->id}
						<label for="subject">Post subject:</label>&nbsp;
						<input id="subject" type="text" class="text" name="post_subject" />
						<hr />
					{/if}
					<textarea style="width: 99%; height: 150px;" name="post_content"></textarea>
					<input type="submit" value="Submit" />
				</div>

			</form>
			{else}
				<span style="float: right;">To {if $post}reply this post{else}submit your post{/if} you need to Log in <a href="/User/Login" title="Login">Login</a> or <a href="/User/Register" title="Registration">Register</a></span>
			{/if}
		</td>
	</tr>
</table>
