<div class="post basket">
	<h2 class="title">{if $category}Products in <a href="/Category/Index/{$category->id}/1">{$category->name}</a>{else}Product list{/if}</h2>

	<p><a href="/ProductAdmin/Edit/" class="ui-state-default ui-corner-all">
		<img src="/resources/icons/mini/icon_wand.gif" alt="New product" /><span>Add new product</span>
	</a></p>

	<div class="post_content">
		
		<fieldset class="ui-widget ui-widget-content ui-corner-all" style="width: 250px;">
			<legend>Find product</legend>
			<p class="quiet">Enter name, id or description</p>
			<form method="get" action="/ProductAdmin/ProductList/">
				<input type="text" name="q" value="{$q}" />
				<input type="submit" value="Search" />
 			</form>
		</fieldset>
	
		<div style="float: left; width: 500px;">
			{include file='admin/product/list.tpl'}
		</div>
	
		<div style="float: left; width: 500px; margin-left: 20px;">
			
		</div>
		
	</div>
</div>
