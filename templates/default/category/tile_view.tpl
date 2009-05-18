<div class="tile_category_view">

	<h3>{$kid_category->name}</h3>

	{if $kid_category->image}
		<a href="/Category/Index/{$kid_category->id}/{$kid_category->name}" title="{$kid_category->name}">
			<img style="padding: 0;" src="/Category/Image/100x100/{$kid_category->id}/{$kid_category->ImageBasename()}" title="{$kid_category->name}" />
		</a>
	{/if}

	<div class="details_button">
		<a href="/Category/Index/{$kid_category->id}/{$kid_category->name}" title="{$kid_category->name}">
			<img src="/resources/images/button_details.png" alt="Details" />
		</a>
	</div>

</div>
