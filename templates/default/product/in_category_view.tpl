<div class="tile_product_view">

	<h3>{$product->name}</h3>

	{assign var=image value=$product->GetMainImage()}
	{if $image->id}
		<a href="/Product/View/{$product->id}/{$product->name}" title="{$product->name}">
			<img style="padding: 0;" src="/Product/Image/100x100/{$image->id}/{$image->GetFilename()}" title="{$image->title}" />
		</a>
	{/if}

		<div class="price">Price: {$smarty.const.CURRENCY_SIGN}{$product->price|string_format:"%.2f"}</div>

	<a href="/Product/View/{$product->id}/{$product->name}" title="{$product->name}">
		<img src="/resources/images/button_details.png" alt="Details" />
	</a>

</div>
