<div class="post">
	{if $category}
    <h2 class="title">
        {if $smarty.session.admin->vendor->id}
            <span style="float: right; margin-right: 10px;  font-size: 9px; text-align: center; font-weight: normal;">
				<a href="/CategoryAdmin/Edit/{$category->id}" title="Edit category">
					<img src="/resources/icons/silk/application_edit.png" alt="Edit" style="padding: 0; margin: 0;" />
					Edit
				</a>
				<a href="/ProductAdmin/InCategoryList/{$category->id}" title="Sort products in this category">
					<img src="/resources/icons/silk/arrow_switch.png" alt="Sort products" />
					Sort
				</a>
				<a href="/CategoryAdmin/Delete/{$category->id}" onclick="return confirm('Do you really want to delete this category?')" title="Delete category">
					<img src="/resources/icons/silk/bomb.png" alt="Delete" />
					Delete
				</a>
			</span>
        {/if}
        <span>{$category->name}</span>
    </h2>

	{*
	<form acrion="{$smarty.server.REWQUEST_URI}" method="get" style="width: 400px; border: 1px solid #808080; -moz-border-radius: 4px; float: right; margin-left: 10px;">
		<div>
			<input name="q" id="q" style="float: left; width: 190px;" value="{$category_search_sentence}" />
			<input type="submit" value="Search in this category" style="float: left; width: 200px;" />
			<br />
		</div>
	</form>
	*}

	<div style="clear: both;"></div>

	{if $category->description->description}
		<div class="entry">
			{if $category->image}<img style="float: right; margin: 0 0 20px 20px;" src="/Category/Image/200x200/{$category->id}/{$category->ImageBasename()}" alt="{$category->name}" />{/if}
			{$category->description->description|nl2br}
		</div>

		<div style="border-bottom: 2px solid #a3a3a3; margin: 20px 0; padding: 0;"></div>
	{else}
		<div style=" margin: 20px 0; padding: 0;"></div>
	{/if}

	<div class="post_content" style="padding: 10px 15px;">
		
			{assign var=kids value=$category->LevelCollection($category->id)}
			{foreach from=$kids item=kid_category}
				{include file='category/tile_view.tpl'}
			{/foreach}

			{if $products}
			{foreach from=$products item=product_mini}
				{include file="product/in_category_view.tpl"}
			{/foreach}
			{/if}
		
		<div style="clear: both;"></div>

		<div>
			{include file="{$config->get('include-path')}/templates/pager.tpl"}
		</div>
	</div>
	{else}
		Category not found.
	{/if}
</div>

