{if $basket}
	<table>
		<thead>
		<tr class="header">
			{if $basket_editable}
				<th style="width: 50px;">Delete</th>
			{/if}
			<th colspan="2">Product</th>
			<th>Price per item</th>
			<th>Quantity</th>
			<th>Total</th>
		</tr>
		</thead>
		<tbody>
	{assign var=basket_totals value=$basket->GetTotals()}

	{foreach from=$basket->items item=item key=item_key}
		{foreach from=$item item=variant key=variant_key}
		{assign var=product value=$basket->GetProduct($item_key)}
		{if $product}
			{assign var=image value=$product->GetMainImage()}
			{assign var=variants value=$basket->GetVariant($variant_key)}
			<tr class="item">
				{if $basket_editable}
					<td class="center">
						{assign var=items value=$items+1}
						<input type="hidden" name="id_{$items}" value="{$items}" />
						<input type="hidden" name="variant_{$items}" value='{$variant_key|escape}' />
						<input type="hidden" name="product_{$items}" value="{$product->id}" />
						<input type="checkbox" name="delete_{$items}" value="{$product->id}" />
					</td>
				{/if}
				<td>
					<a href="http://{$smarty.server.SERVER_NAME}/Product/View/{$product->id}" title="{$product->name}">
						<img style="padding: 0;" src="http://{$smarty.server.SERVER_NAME}/Product/Image/64x64/{$image->id}/{$image->GetFilename()}" title="{$image->title}" />
					</a>
				</td>
				<th><span style="color: black;">{$product->name}</span> {if $variants|@count}<br />(
				{foreach from=$variants item=variant_object name=variantsloop}
				
					{if $variant_object->type=='custom_text'}Custom text: <strong>{$variant_object->value}</strong>{else}{$variant_object->type}: {$variant_object->name}{/if}
					{if not $smarty.foreach.variantsloop.last},{/if}
				{/foreach} )
				{/if}
				</th>
				<td class="center">{$smarty.const.CURRENCY_SIGN}{$variant.item_value*$vat_multiply|string_format:"%.2f"}</td>
				<td class="center">
				{if $basket_editable}
					<input style="width: 40px; text-align: center;" type="text" name="quantity_{$items}" value="{$variant.quantity}" />
				{else}{$variant.quantity}{/if}
				</td>
				<td class="center"><strong>{$smarty.const.CURRENCY_SIGN}{math equation="x * y * vat" x=$variant.item_value y=$variant.quantity vat=$vat_multiply format="%.2f"}</strong></td>
			</tr>
		{/if}
		{/foreach}
	{/foreach}

	{if $shipping}
		<tr class="item">
			<td colspan="2" class="center">
				<strong>Shipping: {$shipping->name}</strong>
				<br />
				{$shipping->description}
			</td>
			<td></td>
			<td></td>
			<th class="center">{$smarty.const.CURRENCY_SIGN}{$shipping->Value($basket_totals.value)*$vat_multiply|string_format:"%.2f"}</th>
		</tr>
	{/if}
		</tbody>
		<tfoot>
			<tr class="footer">
			{if $basket_editable}
				<th class="center">
					{if $items}
						<input type="hidden" name="items" value="{$items}" />
					{/if}
				   &nbsp;
				</th>
			{/if}
				<th colspan="2">
					&nbsp;
				</th>

				<th class="total" style="text-transform: uppercase;">
					Total
				</th>

				<th>
					{$basket_totals.quantity} item{if $basket_totals.quantity > 1}s{/if}
				</th>

				<th>
					{$smarty.const.CURRENCY_SIGN}{if $shipping}{$shipping->ValueWithOrder($basket_totals.value)*$vat_multiply|string_format:"%.2f"}{else}{$basket_totals.value*$vat_multiply|string_format:"%.2f"}{/if}
				</th>

			</tr>
		</tfoot>
	</table>
{else}
	<div>Your basket is empty.</div>
{/if}
