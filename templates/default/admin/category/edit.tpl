<div class="post">
    <h2 class="title">{if $category}category '{$category->name}' editing{else}New category{/if}</h2>

	<div class="post_content">
		<form action="/CategoryAdmin/Edit/{$category->id}" method="post" enctype="multipart/form-data">

			<table>
			
				<tr>
					<td>
					
						<div class="post_cell ui-widget ui-widget-content ui-corner-all">
							<label>Name:</label>
							<br />
							<input class="title" style="width: 80%; font-size: 16px; padding: 0;" type="textinput" name="name" value="{$category->name}" maxlength="255" />

							<br />
							<br />
							<label>Description:</label>
							<br />
							<textarea cols="80" rows="10" name="description">{$category->description->description}</textarea>

							{if $smarty.const.TINY_MCE}
								{include file="admin/tiny_mce.tpl"}
							{/if}
							

							<br />
							<label>Keywords:</label>
							<br />
							<span class="quiet">Separated with commas</span>
							<br />
							<input class="textinput" type="text" name="keywords" value="{$category->keywords}" maxlength="255" />

							<br />
							<label>Meta description:</label>
							<br />
							<textarea cols="80" rows="2" class="mceNoEditor" name="meta_description">{$category->meta_description}</textarea>

						</div>
					</td>
					
					<td style="padding-left: 20px;">
						<div class="post_cell ui-widget ui-widget-content ui-corner-all">
					
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
						
						<br />
						
						<div class="post_cell ui-widget ui-widget-content ui-corner-all">

							<h3>Parent category:</h3>
							<br />
							<div>
								<div style="padding-left: 20px;">
									<input type="radio" name="parent" value="0" id="category_0}" {if !$category->parent}checked="checked"{/if} />
									<label for="category_0">Root</label>
								</div>

							{foreach from=$root_categories item=choose_category}
								<div style="padding-left: 20px;">
									<table>
										<tr>
											<td><input type="radio" name="parent" value="{$choose_category->id}" id="category_{$choose_category->id}" {if $category->parent->id==$choose_category->id}checked="checked"{/if} /></td>
											<td colspan="2"><label for="category_{$choose_category->id}">{$choose_category->name}</label></td>
										</tr>
										
										{assign var=kids value=$choose_category->GetKids()}
										{foreach from=$kids item=kid_category}
										<tr>
											<td></td>
											<td><input type="radio" name="parent" value="{$kid_category->id}" id="category_{$kid_category->id}" {if $category->parent->id==$kid_category->id}checked="checked"{/if} /></td>
											<td><label for="category_{$kid_category->id}">{$kid_category->name}</label></td>
										</tr>
										{/foreach}
									</table>
									
									
								</div>
							{/foreach}
							</div>
						</div>

					</td>
				</tr>
				
				<tr>
					<td>
						<input type="submit" value="Save" class="submit ui-corner-all" style="font-size: 18px;" />
					</td>
					<td>
					</td>
				</tr>
				
			</table>
			




			<div style="clear: both;"></div>
		</form>
	</div>
</div>
