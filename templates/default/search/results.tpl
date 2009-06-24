<div class="post">
	<h2 class="title">Search results for '{$sentence}'</h2>

	<div class="post_content">
		{if !$result}
			<h3>No results, please narrow your <a href="/Search/Advanced/?{$smarty.server.QUERY_STRING}" title="Search for {$sentence}">search</a>.</h3>
		{else}
			{if $result.products}
			<hr />
			<h3>Products</h3>
				{foreach from=$result.products item=product}
				<div>
					<a href="/Product/View/{$product->id}/{$product->name}" title="{$product->description|strip_tags|truncate:80}">{$product->name}</a>
				</div>
				{/foreach}
			<hr />
			{/if}

			{if $result.categories}
			<div class="hr" style="margin: 20px 0;"></div>
			<h3>Categories</h3>
				{foreach from=$result.categories item=category}
				<div>
					<a href="/Category/Index/{$category->id}/1/{$category->name}" title="{$category->GetDescription()|strip_tags|truncate:80}}">{$category->name}</a>
				</div>
				{/foreach}
			<hr />
			{/if}
			<hr />
			<p>
				<a href="/Search/Advanced/?{$smarty.server.QUERY_STRING}" title="Search for {$sentence}">Search again</a>

			</p>
		{/if}
	</div>

</div>
