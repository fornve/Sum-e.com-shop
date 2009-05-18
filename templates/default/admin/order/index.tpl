<div class="post basket">
	<h2 class="title">Orders / Sales</h2>

	<ul class="noprint">
		<li ><a href="/OrderAdmin/Index/" title="Sales">All orders</a></li>
		<li ><a href="/OrderAdmin/Sales/" title="Sales">Sales</a></li>
	</ul>

	{if $sales}
	<div class="samin_sales_box" style="width: 100px; border: 2px solid #526e92; padding: 2px;">
		<div style="color: #fff; background-color: #526e92; font-weight: bold; text-align: center; padding: 1px;" class="ui-icon-ransferthick-e-w">{$sales->count} sales</div>
		<h3 style="text-align: center; color: #202020; font-weight: bold;">{$smarty.const.CURRENCY_SIGN}{$sales->value+0}</h3>
	</div>
	{/if}

	{if $orders}

	<h3 style="padding: 0; margin: 15px 0 0 0;">Order list for {$date_from|date_format:'%e %B'} - {$date_to|date_format:'%e %B'}</h3>

	<table>
		<thead>
			<tr class="header">
				<th>ID</th>
				<th>Date</th>
				<th>Customer</th>
				<th>Value</th>
				<th>Status</th>
				<th class="noprint"></th>
			</tr>
		</thead>
		<tbody>
		{foreach from=$orders item=order name=orderloop}
		{assign var=status value=$order->GetStatus()}
		{if !$display_options.status || $display_options.status == $status}
			<tr class="item">
				<td>{$order->id}</td>
				<td>{$order->purchase_date}</td>
				<td><a href="mailto:{$order->customer_email}">{$order->customer_name}</a> {if $order->customer_phone}( {$order->customer_phone} ){/if}</td>
				<td>{$smarty.const.CURRENCY_SIGN}{$order->value|string_format:"%.2f"}</td>
				<td>{$status}</td>
				<td class="noprint"><a href="/OrderAdmin/View/{$order->id}" title="View"><img src="http://sunforum.co.uk/resources/icons/silk/application.png" alt="View" /></a></td>
			</tr>
		{/if}
		{/foreach}
		</tbody>
	</table>

	{else}
	<h3 style="margin: 15px 0;">No orders for {$date_from|date_format:'%e %B'} - {$date_to|date_format:'%e %B'}.</h3>
	{/if}

	<h2 class="title noprint">Show orders / sales within date range</h2>
	<form method="get" action="{$smarty.server.REQUEST_URI}" class="noprint">
		<div>

			<div style="width: 300px; float: left;">
				<label for="date_from"><h3>Date from</h3></label>
				<div id="date_from_datepicker"></div>
				<input type="hidden" name="date_from" id="date_from" value="{$smarty.session.date_from}" />
			</div>

			<div style="width: 300px; float: right;">
				<label for="date_from"><h3>Date to</h3></label>
				<div id="date_to_datepicker"></div>
				<input type="hidden" name="date_to" id="date_to" value="{$smarty.session.date_to}" />
			</div>

			<div style="clear: both;"></div>

			<br /><br />

			<script type="text/javascript">
				{literal}
				$(function() {
					$("#date_from_datepicker").datepicker({
						currentText: '{/literal}{if $smarty.session.date_from}{$smarty.session.date_from}{else}Today{/if}{literal}',
						onSelect: function(dateText)
						{
							$('#date_from').attr('value',dateText);
						}
					});

					$("#date_to_datepicker").datepicker({
						currentText: '{/literal}{if $smarty.session.date_to}{$smarty.session.date_to}{else}Today{/if}{literal}',
						onSelect: function(dateText)
						{
							$('#date_to').attr('value',dateText);
						}
					});
				});
				{/literal}
			</script>

			<input type="submit" value="Show report">
		</div>
	</form>
</div>