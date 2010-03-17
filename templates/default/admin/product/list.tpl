<table class="admin_table">

	<thead>
		<tr class="header">
			<th>ID</th>
			<th>&nbsp;</th>
			<th>Name / Category</th>
			<th style="font-size: smaller;">Available / Sold</th>
			<th>Visits *</th>
			<th>Price</th>
			<th>Status</th>
			{if $category}<th>List order</th>{/if}
			<th style="width: 60px;">Action</th>
		</tr>
	</thead>

	<tbody>
	{foreach from=$products item=product name=productloop}
		<tr class="item">
			<td>{$product->id}</td>
			<td>
				{assign var=image value=$product->GetMainImage()}
				{if $image->id}
					<img style="padding: 0;" src="/Product/Image/24x24/{$image->id}/{$image->GetFilename()}" title="{$image->title}" />
				{/if}
			</td>
			<td>
				<a href="/Product/View/{$product->id}">{$product->name}</a>
					<br />
				{if $product->categories}
					{strip}
					[{foreach from=$product->categories item=category_id name=product_category_loop}
						{assign var=product_category value=$product->GetCategory($category_id)}
						<a href="/ProductAdmin/InCategoryList/{$product_category->id}">{$product_category->name}</a>
						{if !$smarty.foreach.product_category_loop.last}, {/if}
					{/foreach}]
					{/strip}
				{else}
					<img src="/resources/icons/silk/error.png" alt="Warning">
					<span style="font-size: smaller;">Product is not assigned to any category.</span>
				{/if}
			</td>

			<td style="text-align: center;">{$product->quantity+0} / {$product->sold+0}</td>
			<td style="text-align: center;">{$product->visited}</td>
			<td>{$smarty.const.CURRENCY_SIGN}{$product->price|string_format:"%.2f"}</td>
			<td style="text-align: center;">
				{if $product->status}
					<a href="/ProductAdmin/ProductList/?disable={$product->id}" title="Enabled{if $product->quantity<1} - but stock quantity &lt; 1{/if}"><img src="/resources/icons/mini/flag_{if $product->quantity}green{else}orange{/if}.gif" alt="Available" /></a>
				{else}
					<a href="/ProductAdmin/ProductList/?enable={$product->id}" title="Disabled"><img src="/resources/icons/mini/flag_red.gif" alt="Not available" /></a>
				{/if}
			</td>

			{if $category}
			<td style="text-align: center;">

				<a href="/ProductAdmin/ListOrderUp/{$product->id}/{$category->id}" title="Move product up in this category">
					<img src="/resources/icons/silk/arrow_up.png" alt="Up">
				</a>

				<a href="/ProductAdmin/ListOrderDown/{$product->id}/{$category->id}" title="Move product down in this category">
					<img src="/resources/icons/silk/arrow_down.png" alt="Down">
				</a>

			</td>
			{/if}

			<td>
				<a href="/ProductAdmin/Edit/{$product->id}" title="Edit"><img src="/resources/icons/silk/application_edit.png" alt="Edit" /></a>
				<a href="/Product/View/{$product->id}" title="View"><img src="/resources/icons/silk/application_go.png" alt="View" /></a>
				<a href="/ProductAdmin/Delete/{$product->id}" onclick="return confirm('Do you really want to delete product: {$product->name}?')" title="Delete"><img src="/resources/icons/silk/application_delete.png" alt="Delete" /></a>
			</td>
		</tr>
		{assign var=shop_products_quantity value=$shop_products_quantity+1}
	{/foreach}
	</tbody>
</table>

<div>
	{include file='/var/www/include/templates/pager.tpl'}
</div>

<div>
	Products in shop: {$products_in_shop}
</div>

<div>
	<br />
	<sup>*)</sup><span>To refresh <a href="/SettingsAdmin/FlushCache/">flush cache</a></span>
</div>
