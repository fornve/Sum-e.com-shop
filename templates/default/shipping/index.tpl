<div class="basket post">

	{assign var=checkout_stage value=2}
	{include file=checkout/checkout_stages.tpl}
	
    <h2 class="title">Shipping</h2>

	<div class="post_content">
		<form action="/Shipping/View/" method="post">
			<p>Your basket value is <strong>{$smarty.const.CURRENCY_SIGN}{$basket_total.value}</strong></p>
			<div>
				<table>
				{foreach from=$shippings item=shipping}
					<tr>
						<th>{$shipping->name}</th>
						<td>{if $shipping->Value($basket_total.value) < $shipping->flat_value}Free{else}{$smarty.const.CURRENCY_SIGN}{$shipping->flat_value*$vat_multiply|string_format:"%.2f"}{/if}</td>
						<td>
							<input type="radio" name="shipping" value="{$shipping->id}" {if $smarty.session.shipping==$shipping->id}checked="checked"{/if} />
						</td>
						<td>{$shipping->description}</td>
					</tr>
				{/foreach}
				</table>
				<div>

					<input type="image" src="/resources/images/checkout_button.png" alt="Checkout" />

					<div style="clear: both;"></div>
				</div>
			</div>

		</form>
	</div>
</div>