<div class="post">
    <h2 class="title">{if $category}category '{$category->name}' editing{else}New category{/if}</h2>

	<div class="post_content">
		<form action="/CategoryAdmin/Edit/{$category->id}" method="post" enctype="multipart/form-data">

			<br />
			<label>Name:</label>
			<br />
			<input class="title" type="text" name="name" value="{$category->name}" maxlength="255" />

			<br />
			<label>Description:</label>
			<br />
			<textarea cols="80" rows="10" name="description">{$category->description->description}</textarea>

			{if $smarty.const.TINY_MCE}
				{include file="admin/tiny_mce.tpl"}
			{/if}

			<div style="float: right; width: 300px; border: 2px solid #434343; -moz-border-radius: 10px; padding: 10px; margin: 10px 0; ">

				<h3>Image</h3>
				{if $category->image}
				<div>
					<img src="/Category/Image/150x150/{$category->id}" />
				</div>

					<label for="delete_image">Delete image</label>
					<input type="checkbox" name="delete_image" id="delete_image" value="1" />
				{/if}
				<br />
				<label>Select new image from disc:</label>
				<br />
				<input class="text" type="file" name="image" value="" maxlength="255" />
			</div>

			<div style="width: 250px; border: 2px solid #434343; -moz-border-radius: 10px; padding: 10px; margin: 10px 0; ">

				<h3>Parent category:</h3>
				<br />
				<div>
					<div style="padding-left: 20px;">
						<input type="radio" name="parent" value="0" id="category_0}" {if !$category->parent}checked="checked"{/if} />
						<label for="category_0">Root</label>
					</div>

				{foreach from=$root_categories item=choose_category}
					<div style="padding-left: 20px;">
						<input type="radio" name="parent" value="{$choose_category->id}" id="category_{$choose_category->id}" {if $category->parent->id==$choose_category->id}checked="checked"{/if} />
						<label for="category_{$choose_category->id}">{$choose_category->name}</label>
					</div>
				{/foreach}
				</div>
			</div>

			<input style="float: right; clear: both;" type="image" src="/resources/images/button_save.png" value="Save" />

			<div style="clear: both;"></div>
		</form>
	</div>
</div>
