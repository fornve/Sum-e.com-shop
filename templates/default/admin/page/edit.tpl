<div class="post">
	<h2 class="title">{if $page}Page: '{$page->title}' editing{else}New Page{/if}</h2>

	<div class="post_content ui-widget ui-widget-content ui-corner-all">
		<form action="/PageAdmin/Edit/{$page->id}" method="post" enctype="multipart/form-data">
			<table>
				<tr>
					<td>
						<div class="post_cell ui-widget ui-widget-content ui-corner-all">
							<label>Title</label>
							<br />
							<input class="textinput" type="text" name="title" value="{$page->title}" maxlength="255" />

							<br />
							<label>Content:</label>
							<br />
							<textarea cols="80" rows="10" name="text">{$page->text}</textarea>

							{if $smarty.const.TINY_MCE}
								{include file="admin/tiny_mce.tpl"}
							{/if}
						</div>
					</td>

					<td>
						<div class="post_cell ui-widget ui-widget-content ui-corner-all">
							<label>Type</label>
							<br />
							<select name="type">
								<option value="">Blog</option>
								<option value="tnc" {if $page->type=='tnc'}selected="selected"{/if}>Terms and Conditions</option>
								<option value="about" {if $page->type=='about'}selected="selected"{/if}>About us</option>
							</select>
						</div>

						<div class="post_cell ui-widget ui-widget-content ui-corner-all">
							<h3>Image</h3>
							{if $page->image}
							<div>
								<img src="/Page/Image/150x150/{$page->id}" />
							</div>

								<label for="delete_image">Delete image</label>
								<input type="checkbox" name="delete_image" id="delete_image" value="1" />
							{/if}
							<br />
							<label>Select new image from disc:</label>
							<br />
							<input class="text" type="file" name="image" value="" maxlength="255" />
						</div>

					</td>

				</tr>
					
				<tr>
					<td colspan="2" style="padding-top: 10px;">
						<input type="submit" value="Save" class="submit ui-state-default ui-corner-all" style="font-size: 11px;" />
					</td>
				</tr>
			</table>
		</form>
		<br />
	</div>
</div>
