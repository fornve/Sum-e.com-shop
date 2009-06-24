<div class="post">
	<h2 class="title" stype="padding-top: 20px;">{$page->title|stripslashes}</h2>
	<div class="post_content">
		{if $page->image}
		<div style="float: right; margin: 0 0 20px 20px;">
			<img src="/Page/Image/250x250/{$page->id}" alt="{$page->title|stripslashes}" />
		</div>
		{/if}

		{$page->text|nl2br|stripslashes}
	</div>
</div>