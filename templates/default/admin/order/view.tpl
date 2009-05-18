<div class="post basket">
	<h2 class="title noprint">Order preview</h2>

	<div style="position: relative;" class="toolbar noprint" class="noprint" onmouseout="jQuery('#search_help').hide();" onmouseover="jQuery('#search_help').show();">
		<img  onclick="javascript:window.print(); return false;" src="http://sunforum.co.uk/resources/icons/silk/printer.png" alt="Print invoice" title="Print invoice" />
	
		<div id="search_help" class="popup noprint" onmouseout="jQuery('#search_help').hide();" style="width: 300px; position: absolute; top: 20px; left: 0;">
			{include file='admin/help/printing.tpl'}
		</div>
	</div>

	<h1>Order {$order->id}</h1>

	<h3 style="margin-top: 15px;">Delivery details</h3>
	<div>
		<div>{$order->customer_name}</div>
		<div>{$order->customer_address}</div>
		<div>{$order->customer_postcode}, {$order->customer_city}</div>
		<div><img src="http://sunforum.co.uk/resources/icons/flag/{$order->customer_country|lower}.png" alt="{$order->customer_country}" /> <span>{$customer_country->name}</span></div>
		<div>{$customer->email}</div>
		{if $order->customer_phone}<div>{$order->customer_phone}</div>{/if}
		{if $order->customer_note}<div>{$order->customer_note}</div>{/if}

	</div>

	<h3 style="margin-top: 15px;">Order details</h3>

    {include file="basket/basket-table.tpl"}

	{if $update}
		{include file='admin/order/update_form.tpl'}
	{/if}

	{if $order->status_history}<h3 style="margin-top: 15px;" class="noprint">Order history</h3>

		<table class="noprint">
			<thead>
				<tr class="header">
					<th>ID</th>
					<th style="width: 200px;">Created</th>
					<th>Status</th>
					<th>Payer ID</th>
					<th>Transaction ID</th>
					<th>Total</th>
				<tr>
			</thead>
			<tbody>
			{foreach from=$order->status_history item=status}
				<tr class="item">
					<td>{$status->id}</td>
					<td style="font-size: smaller;">{$status->created}</td>
					<td>{$status->status}</td>
					<td>{$status->payer_id}</td>
					<td>{$status->transaction_id}</td>
					<td>{$status->total}</td>
				</tr>
			{/foreach}
			</tbody>
		</table>
	{/if}

	<div class="noprint">
	{if $order->GetStatus()=='Completed' || $order->GetStatus()=='Undespatched'}
		<ul>
			<li><a href="/OrderAdmin/Despatch/{$order->id}">Despatch</a></li>
			<li><a href="/OrderAdmin/UnComplete/{$order->id}">Uncomplete</a></li>
		</ul>
	{elseif $order->GetStatus()=='Despatched'}
		<ul>
			<li><a href="/OrderAdmin/UnDespatch/{$order->id}">Undespatch</a></li>
		</ul>
	{else}
		<ul>
			<li><a href="/OrderAdmin/Update/{$order->id}">Update</a></li>
		</ul>
	{/if}
	</div>

</div>
