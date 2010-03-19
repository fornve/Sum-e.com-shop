<div class="post product_edit">

<script type="text/javascript">{literal}

		jQuery(function() {
			jQuery("#tabs").tabs();
		});
		
    function toogleTabs(id)
    {
        jQuery('.tab').hide();
        jQuery('.tabs li').removeClass('selected');
        jQuery('#'+id).show();
        jQuery('#tab_'+id).addClass('selected');
        jQuery('#active_tab').attr('value',id);
    }
    {/literal}
</script>


    <h2 class="title">{if $product}Product '{$product->name}' editing{else}New product{/if}</h2>
	<div class="post_content">
		<form action="/ProductAdmin/Edit/{$product->id}" method="post" enctype="multipart/form-data">

			<div id="tabs">


				<ul class="tabs">
					<li id="tab_general" {if !$smarty.post.active_tab || $smarty.post.active_tab=='general'}class="selected"{/if}><a href="#general" onclick="toogleTabs('general'); return false;">General</a></li>
					<li id="tab_data" {if $smarty.post.active_tab=='data'}class="selected"{/if}><a href="#data" onclick="toogleTabs('data'); return false;">Data</a></li>
					<li id="tab_images" {if $smarty.post.active_tab=='images'}class="selected"{/if}><a href="#images" onclick="toogleTabs('images'); return false;">Images</a></li>
					{*<li id="tab_variants" {if $smarty.post.active_tab=='various'}class="selected"{/if}><a href="#variants" onclick="toogleTabs('variants'); return false;">Variants</a></li>*}
					<li id="tab_categories" {if $smarty.post.active_tab=='categories'}class="selected"{/if}><a href="#categories" onclick="toogleTabs('categories'); return false;">Categories</a></li>
				</ul>

				<input type="hidden" name="active_tab" id="active_tab" value="{if $smarty.post.active_tab}{$smarty.post.active_tab}{else}general{/if}" />

				<div id="general" class="tab" {if !$smarty.post.active_tab || $smarty.post.active_tab=='general'}style="display: block;"{/if}>

					<label>Name:</label>
					<br />
					<input class="textinput" type="text" name="name" value="{$product->name}" maxlength="255" />

					<br />
					<label>UPC:</label>
					<br />
					
					<div class="quiet">Unique product code</div>
					<input class="textinput" type="text" name="upc" value="{$product->upc}" maxlength="255" />

					<br />
					<label>Description:</label>
					<br />
					<textarea cols="80" rows="10" name="description">{$product->description}</textarea>

					<br />
					<label>Keywords:</label>
					<div type="hint">Separated with commas</div>
					<br />
					<input class="textinput" type="text" name="keywords" value="{$product->keywords}" maxlength="255" />

					<br />
					<label>Condition:</label>
					<br />
					<input class="textinput" type="text" name="condition" value="{$product->condition}" maxlength="255" />

					<br />
					<label>Status:</label>
					<br />
					<select name="status">
						<option value="0" {if $product->status==0}selected="selected"{/if}>Not Available</option>
						<option value="1" {if $product->status==1}selected="selected"{/if}>Available</option>
					</select>

					{if $smarty.const.TINY_MCE}
						{include file="admin/tiny_mce.tpl"}
					{/if}

				</div>

				<div id="data" class="tab"{if $smarty.post.active_tab=='data'} style="display: block;"{/if}>

					<label>Price (net):</label>
					<br />
					<input class="textinput" type="text" name="price" value="{$product->price}" maxlength="255" />

					<br />
					<label>Tax:</label>
					<br />
					<select name="tax">
						<option>No tax</option>
						{foreach from=$taxes item=tax}
						<option value="{$tax->id}" {if $product->tax->id==$tax->id}selected="selected"{/if}>{$tax->name}</option>
						{/foreach}
					</select>

					<br />
					<label>Quantity:</label>
					<br />
					<input class="textinput" type="text" name="quantity" value="{if $product->variants}See 'Variants' tab" disabled="disabled{else}{$product->quantity}{/if}" maxlength="255" />

					<br />
					<label>Weight:</label>
					<br />
					<div type="hint">In grams</div>
					<input class="textinput" type="text" name="weight" value="{$product->weight}" maxlength="255" />

					<br />
					<label>Storage location:</label>
					<br />
					<input class="textinput" type="text" name="storage_location" value="{$product->storage_location}" maxlength="255" />

				</div>

				<div id="images" class="tab"{if $smarty.post.active_tab=='images'} style="display: block;"{/if}>

					{foreach from=$product->images item=image}
						<div style="border: 1px dashed #434343; margin: 10px; padding: 10px; width: 150px; float: left;">
							<img style="padding: 0;" src="/Product/Image/150x150/{$image->id}/{$image->GetFilename()}" title="{$image->title}" />

							<br />
							<label>Image title:</label>
							<br />
							<input class="textinput" type="text" name="image_title_{$image->id}" value="{$image->title}" maxlength="255" style="width: 140px;" />

							<br />
							<label>Primary: [{$image->id} - {$image->main}]</label>
							<br />
							<input type="radio" name="main_image" {if $image->main}checked="checked"{/if} value="{$image->id}" />

							<br />
							<label>Delete  image:</label>
							<br />
							<input type="checkbox" name="image_delete_{$image->id}" value="1" />

						</div>
					{/foreach}

					<div style="clear: both;"></div>
					<br />
					<label>Select new image from disc:</label>
					<br />
					<input class="textinput" type="file" name="image" value="" maxlength="255" />

				</div>

				{*
				<div id="variants" class="tab"{if $smarty.post.active_tab=='variants'} style="display: block;"{/if}>

					<table>
						<thead>
							<tr>
								<th>Delete</th>
								<th>Type</th>
								<th>Name</th>
								<th>Base price change (%)</th>
								<th>Quantity</th>
							</tr>
						</thead>
						<tbody>
						{foreach from=$product->variants item=variant}
							<tr>
								<td>
									<input type="checkbox" name="delete_variant_{$variant->id}" value="{$variant->id}" />
								</td>
								<td>{$variant->type}</td>
								<td>{$variant->name}</td>
								<td>
									<input type="textinput" name="variant_price_change_{$variant->id}" value="{$variant->price_change}" style="width: 50px;" />%
								</td>
								<td>
									<input type="textinput" name="variant_quantity_{$variant->id}" value="{$variant->quantity}" style="width: 50px;" />
								</td>
							</tr>
						{/foreach}
						</tboody>
						<tfooter>
							<tr>
								<td>New</td>
								<td>
									<input type="textinput" name="new_variant_type" style="width: 100px;" value="" />
								</td>
								<td>
									<input type="textinput" name="new_variant_name" style="width: 150px;" value="" />
								</td>
								<td>
									<input type="textinput" name="new_variant_price_change" style="width: 50px;" value="" />
								</td>
								<td>
									<input type="textinput" name="new_variant_quantity" style="width: 50px;" value="" />
								</td>
							</tr>
						</tfooter>
					</table>
					*}
					
				</div>

				<div id="categories" class="tab"{if $smarty.post.active_tab=='categories'} style="display: block;"{/if}>

				<ul class="category_tree_parent">
				{foreach from=$category_tree item=category}
					<li>
						<input type="checkbox" name="category_{$category->id}" value="{$category->id}" {if $product}{if $product->InCategory($category->id)}checked="checked"{/if}{/if} />
						<span onclick="$('#kid_{$category->id}').toggle('fast')">
							{if $category->kids}
								<img src="/resources/icons/silk/bullet_toggle_plus.png" alt="Expand/Collapse category">
							{else}
								<img src="/resources/icons/silk/bullet_white.png" />
							{/if}
							{$category->name}
						</span>


					{if $category->kids > 0}
						<ul id="kid_{$category->id}" class="category_tree_kid" {if $product}{if $product->InBranch($category->id)}style="display: block;"{/if}{/if}>
						{foreach from=$category->kids item=kid}
							<li>
								<input type="checkbox" name="category_{$kid->id}" value="{$kid->id}" {if $product}{if $product->InCategory($kid->id)}checked="checked"{/if}{/if} />
									<img src="/resources/icons/silk/bullet_white.png" />
								<span>{$kid->name}</span>
							</li>
						{/foreach}
						</ul>
					{/if}
					</li>
				{/foreach}
				</ul>

				</div>

				<div style="border-top: 1px solid #434343; margin: 15px 0 20px 0;"></div>

				<input type="submit" value="{$lang->SAVE}" class="ui-state-default ui-corner-all" style="color: #000; padding: 5px 20px;" />
			</div>

		</form>
	</div>
</div>
