
		<form action="https://www.{if not $smarty.const.PRODUCTION}sandbox.{/if}paypal.com/uk/cgi-bin/webscr" method="post">
			<div>
				<input name="cmd" value="_cart" type="hidden" />

				<input name="upload" value="1" type="hidden" />
				<input name="business" value="{$smarty.const.PAYPAL_ACCOUNT_EMAIL}" type="hidden" />
				<input name="currency_code" value="GBP" type="hidden" />
				<input name="cancel_return" value="http://{$smarty.server.SERVER_NAME}/Payment/Cancelled/{$smarty.session.order}" type="hidden" />
				<input name="cbt" value="Continue" type="hidden" />
				<input name="notify_url" value="http://{$smarty.server.SERVER_NAME}/Payment/PaypalIPN/{$smarty.session.order}" type="hidden" />
				<input name="return" value="http://{$smarty.server.SERVER_NAME}/Checkout/Success/{$smarty.session.order}" type="hidden" />
				<input name="rm" value="2" type="hidden" />

				{foreach from=$basket->items item=item key=item_key}
					{foreach from=$item item=variant key=variant_key name=basketloop}
						{assign var=product value=$basket->GetProduct($item_key)}
						{if $product}
							{assign var=variants value=$basket->GetVariant($variant_key)}
							{assign var=items value=$items+1}
							<input name="item_name_{$items}" value="{$product->name}{if $variants|@count} ({foreach from=$variants item=variant_object name=variantsloop}{$variant_object->type}: {$variant_object->name}{if not $smarty.foreach.variantsloop.last},{/if}{/foreach} ){/if}" type="hidden" />
							<input name="quantity_{$items}" value="{$variant.quantity}" type="hidden" />
							<input name="amount_{$items}" value="{$variant.item_value|string_format:'%.2f'}" type="hidden" />
							<input name="tax_rate_{$items}" value="{$tax_rate}" type="hidden" />
						{/if}
					{/foreach}
				{/foreach}

				{assign var=basket_totals value=$basket->GetTotals()}
				
				{if $shipping}
					{assign var=items value=$items+1}
					{assign var=shipping_totals value=$basket->GetTotals()}
					<input name="item_name_{$items}" value="Delivery: {$shipping->name}" type="hidden" />
					<input name="quantity_{$items}" value="1" type="hidden" />
					<input name="amount_{$items}" value="{$shipping->Value($shipping_totals.value)|string_format:'%.2f'}" type="hidden" />
				{/if}

				<input name="email" value="{$customer->email}" type="hidden" />
				<input name="first_name" value="{$customer->firstname}" type="hidden" />
				<input name="last_name" value="{$customer->lastname}" type="hidden" />
				<input name="address1" value="{$customer->address1}" type="hidden" />
				<input name="address2" value="{$customer->address2}" type="hidden" />
				<input name="city" value="{$customer->city}" type="hidden" />
				<input name="zip" value="{$customer->postcode}" type="hidden" />
				<input name="state" value="" type="hidden" />
				<input name="day_phone_a" value="{$customer->phone}" type="hidden" />
				<input class="paypalgateway" value="Finish transaction at PayPal page" type="submit" onclick="clean_basket();"/>
			</div>
        </form>