<div class="post basket">

	{assign var=checkout_stage value=4}
	{include file=checkout/checkout_stages.tpl}
	
	<h2 class="title">Review your order and choose payment method</h2>

	<div class="post_content">
		<div class="post_content">
			<h3 style="margin-top: 15px;">Delivery details</h3>
			<div>
				<div>{$customer->firstname} {$customer->lastname}</div>
				<div>{$customer->address1}</div>
				{if $customer->address2}<div>{$customer->address2}</div>{/if}
				<div>{$customer->postcode}, {$customer->city}</div>
				<div><img src="http://sunforum.co.uk/resources/icons/flag/{$customer->country|lower}.png" alt="{$customer->country}" /> <span>{$customer_country->name}</span></div>
				<div>{$customer->email}</div>
				{if $customer->phone}<div>{$customer->phone}</div>{/if}
				{if $customer->note}<div>{$customer->note}</div>{/if}

			</div>

			<h3 style="margin-top: 15px;">Your order</h3>

			{include file="basket/basket-table.tpl"}

			<a href="#" onclick="show_paypal();">Paypal</a>
			<a href="#" onclick="show_visa_form();">Visa</a>
			<a href="/Payment/PostalOrder/">Postal Order</a>

			<div id="paypal_form">
				{include file='payment/paypal_express_checkout_form.tpl'}
			</div>
		</div>
	</div>
</div>
