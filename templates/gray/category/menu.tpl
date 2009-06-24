{if $categories}
	<li class="header"><h2>Categories</h2></li>
{foreach from=$categories item=category_list_item}
	<li {if $category_list_item->id==$category->id || $category_list_item->id==$category->parent->id} class="selected"{/if}>
		<a href="/Category/Index/{$category_list_item->id}/1/{$category_list_item->name}" title="{$category_list_item->name}"{* onclick="return showCategory({$category_list_item->id});"*}>{$category_list_item->name}</a>
		{if $category_list_item->id==$category->id && $category}
			{assign var=category_menu_kids value=$category->LevelCollection($category->id)}
		{elseif $category_list_item->id==$category->parent->id && $category}
			{assign var=category_menu_kids value=$category->LevelCollection($category->parent->id)}
		{/if}

		{if $category_menu_kids}
			<ul>
			{foreach from=$category_menu_kids item=category_kid}
				<li>
					<a href="/Category/Index/{$category_kid->id}/1/{$category_kid->name}" title="{$category_kid->name}" {*onclick="return showCategory({$category_kid->id});"*}>{$category_kid->name}</a>
				</li>
			{/foreach}
			</ul>
		{/if}

		{assign var=category_menu_kids value=0}
	</li>
{/foreach}
{/if}
