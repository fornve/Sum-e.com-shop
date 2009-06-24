<div class="tile_product_view">

	<h3>{$product_mini->name}</h3>

	{assign var=product_mini_image value=$product_mini->GetMainImage()}
	{if $product_mini_image->id}
		<a href="/Product/View/{$product_mini->id}/{$product_mini->name}" title="{$product_mini->name}">
			<img style="padding: 0;" src="/Product/Image/100x100/{$product_mini_image->id}/{$product_mini_image->GetFilename()}" title="{$product_mini_image->title}" />
		</a>
	{/if}

		<div class="price">Price: {$smarty.const.CURRENCY_SIGN}{$product_mini->price*$vat_multiply|string_format:"%.2f"}</div>

	<a href="/Product/View/{$product_mini->id}/{$product_mini->name}" title="{$product_mini->name}">
		<img src="/resources/images/button_details.png" alt="Details" />
	</a>

</div>