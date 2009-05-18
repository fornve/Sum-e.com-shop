<div class="basket post">
    <h2 class="title">Review your order</h2>

	<h3 style="margin-top: 15px;">Your order</h3>

    {include file="basket/basket-table.tpl"}

	<div>
		
		<div style="width: 200px; float: left;">
			<a href="/Basket/View" title="Back to basket">
				<img src="/resources/images/basket_back_button.png" alt="Basket">
			</a>
		</div>

		<div style="width: 200px;">
			<a href="/Checkout/PaymentMethod/" title="Proceed checkout">
				<img src="/resources/images/checkout_button.png" alt="Checkout">
			</a>
		</div>

		<div style="clear: both;"></div>

	</div>
</div>