<div class="post basket">
	<h2 class="title">{if $category}Products in <a href="/Category/Index/{$category->id}/1">{$category->name}</a>{else}Product list{/if}</h2>

	<p><a href="/ProductAdmin/Edit/" class="ui-state-default ui-corner-all">
		<img src="/resources/icons/mini/icon_wand.gif" alt="New product" /><span>Add new product</span>
	</a></p>

	<div class="post_content">
		{include file='admin/product/list.tpl'}
	</div>
</div>
