<h2 class="alt">Forum: {$forum->name}</h2>

<h1 class="alt">{$subject->subject}</h1>

<table class="forumtable">
	<tr>
		<th width="100px;">Author</th>
		<th>Message</th>
	</tr>

	{foreach from=$subject->posts item=post}
	<tr>
		<td><a href="#">{$post->user->username}</a></td>
		<td>
			<div class="quiet">
				Posted on {$post->posted|date_format:'%Y-%m-%d %H:%m'}
				{if $user && ( $user->id==$post->user->id || $user->HasRole('admin'))}<a href="/Forum/DeletePost/{$post->id}">Delete post</a>{/if}
			</div>
			<hr />
			{$post->content}
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
			<form action="/Forum/NewPost/{$forum->id}/{$subject->id}/{$subject->subject}" method="post">
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
