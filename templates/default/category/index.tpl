<div class="post">
    <h2 class="title">
        {if $smarty.session.admin->vendor->id}
            <span style="float: right; font-size: smaller; margin-right: 10px;"><a href="/CategoryAdmin/Edit/{$category->id}">Edit</a> / <a href="/CategoryAdmin/Delete/{$category->id}" onclick="return confirm('Do you really want to delete this category?')">Delete</a> </span>
        {/if}
        <span>{$category->name}<span>
    </h2>

	<form acrion="{$smarty.server.REWQUEST_URI}" method="get" style="width: 400px; border: 1px solid #808080; -moz-border-radius: 4px; float: right; margin-left: 10px;">
		<div>
			<input name="q" id="q" style="float: left; width: 190px;" value="{$category_search_sentence}" />
			<input type="submit" value="Search in this category" style="float: left; width: 200px;" />
			<br />
		</div>
	</form>

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

	{assign var=kids value=$category->LevelCollection($category->id)}
	{foreach from=$kids item=kid_category}
		{include file='category/tile_view.tpl'}
	{/foreach}

	{if $products}
    {foreach from=$products item=product}
		{include file="product/in_category_view.tpl"}
	{/foreach}
	{/if}

    <div style="clear: both;"></div>
</div>
