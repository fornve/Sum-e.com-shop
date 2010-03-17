<div class="post product">

    <h2 class="title">
		{if $smarty.session.admin->vendor->id==$product->vendor->id}
             <span style="float: right; margin-right: 10px;  font-size: 9px; text-align: center; font-weight: normal;">
				<a href="/ProductAdmin/Edit/{$product->id}" title="Edit category">
					<img src="/resources/icons/silk/application_edit.png" alt="Edit" style="padding: 0; margin: 0;" />
					Edit
				</a>
			</span>
       {/if}

        <span>{$product->name}</span>
    </h2>

	<div class="post_content">

		{assign var=image value=$product->GetMainImage()}
		{if $image->id}
			<div style="float: left; margin: 10px 10px 10px 0; padding:  10px 10px 10px 0; position: relative;">
				<img style="padding: 0;" src="/Product/Image/240x240/{$image->id}/{$image->GetFilename()}" title="{$image->title}" class="thickbox" />

				<a href="/Product/Image/0/{$image->id}/{$image->GetFilename()}" class="thickbox" style="display: block; position: absolute; bottom: 0; left: 0;" alt="Zoom">
					<img src="/resources/icons/silk/magnifier.png" alt="Zoom" />
				</a>
			</div>
		{/if}

		{if $product->price > 0}<h3 class="price" style=" font-size: 22px; margin: 30px 0 0 320px;">Price: &pound;{$product->price*$vat_multiply|string_format:"%.2f"}</h3>{/if}

		{if $product->IsForSale()}
		<div>
			<form action="/Basket/Add/{$product->id}" method="post">
				{if $product->variants}

					{foreach from=$product->variants item=variant}
						{if $new_type!=$variant->type}
							{assign var=new_type value=$variant->type}
							<h5>{$new_type}</h5><ul>
						{/if}
						<li>
							<input type="radio" value="{$variant->id}" id="variant_{$variant->id}" name="variant_{$variant->type}" />
							<label for="variant_{$variant->id}"><strong>{$variant->name}</strong></label>
							<span>Price change: {if $variant->price_change>0}+{/if}{$variant->price_change} %</span>
						</li>
					{/foreach}

				</ul>
				{/if}
				<div style="margin: 10px 80px 30px 20px; float: right; width: 200px;">
					<div style="float: left; width: 40px; margin: 17px 10px 0 0;">
						<input type="text" id="quantity" name="quantity" value="1" style="width: 30px; font-size: 20px; text-align: center;" maxlength="10" />
					</div>
					<div style="float: left; width: 100px;padding: 10px 0 0 0;">
						<input type="image" src="/resources/images/button_buy.png" alt="Buy" value="Add to basket" onclick="return addProductToBasket({$product->id});" />
					</div>

				</div>
			</form>
		</div>
		{else}
		<div style="padding-top: 10px;">
			Not available.
		</div>
		{/if}

		{if $product->condition}<div style="margin: 10px 0; clear: right;">Condition: {$product->condition}</div>{/if}

		<div style="clear: both;"></div>

		<div class="description">{$product->description|nl2br}</div>

		{if $product->images|@count > 1}
		<div style="clear: both;">
			{foreach from=$product->images item=image}

				<div style="float: left; padding: 10px; margin: 10px;">
					<a href="/Product/Image/600x400/{$image->id}/{$image->GetFilename()}" class="thickbox">
						<img src="/Product/Image/100x100/{$image->id}/{$image->GetFilename()}" title="{$image->title}" />
					</a>
				</div>

			{/foreach}
			<div style="clear: both;"></div>
		</div>
		{/if}

		{if $related_products}
			<div class="hr" style="margin: 20px 0;"></div>
			
			<h3>Related products:</h3>
			{foreach from=$related_products item=product_mini}
				{include file="product/in_category_view.tpl"}
			{/foreach}
			<div style="clear: both;"></div>
		{/if}
	</div>
</div>
