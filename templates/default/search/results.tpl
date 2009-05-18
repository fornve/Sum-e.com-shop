<div class="post">
	<h2 class="title">Search results for '{$sentence}'</h2>

	{if !$result}
		<h3>No results, please narrow your search.</h3>
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
		<hr />
		<h3>Categories</h3>
			{foreach from=$result.categories item=category}
			<div>
				<a href="/Category/Index/{$category->id}/{$category->name}" title="{$category->GetDescription()|strip_tags|truncate:80}}">{$category->name}</a>
			</div>
			{/foreach}
		<hr />
		{/if}
	{/if}

</div>