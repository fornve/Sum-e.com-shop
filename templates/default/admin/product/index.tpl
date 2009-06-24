<div class="post basket">
    <h2 class="title">{if $category}Products in <a href="/Category/Index/{$category->id}/1">{$category->name}</a>{else}Product list{/if}</h2>

	<div class="post_content">
		{include file='admin/product/list.tpl'}
	</div>
</div>
