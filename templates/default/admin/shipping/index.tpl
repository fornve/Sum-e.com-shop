<div class="post basket">
	<h2 class="title">Shipping manager</h2>

	<div class="post_content">
		<h3>Available modules</h3>

		<table>

			<thead>
				<tr class="header">
					<th>ID</th>
					<th>Name</th>
					<th><span>Flat Value</span><br /><span class="hint">(initial)</span></th>
					{*<th><span>Weight multiply</span><br /><span class="hint">[per product kg]</span></th>*}
					<th><span>Price limit</span><br /><span class="hint">(when shipping is free)</span></th>
					<th>Enabled</th>
					<th>&nbsp;</th>
				</tr>
			</thead>

			<tbody>
			{foreach from=$shippings item=shipping}
				<tr>
					<td class="center">{$shipping->id}</td>
					<td>{$shipping->name}</td>
					<td style="text-align: center;">{$smarty.const.CURRENCY_SIGN}{$shipping->flat_value}</td>
					{*<td>{$shipping->weight_multiply}</td>*}
					<td style="text-align: center;">{$smarty.const.CURRENCY_SIGN}{$shipping->limit_price}</td>
					<td style="text-align: center;">
						{if $shipping->enabled}
							<a href="/ShippingAdmin/Index/?disable={$shipping->id}"><img src="http://sunforum.co.uk/resources/icons/mini/flag_green.gif" alt="Enabled" /></a>
						{else}
							<a href="/ShippingAdmin/Index/?enable={$shipping->id}"><img src="http://sunforum.co.uk/resources/icons/mini/flag_red.gif" alt="Disabled" /></a>
						{/if}
					</td>
					<td>
						<a href="/ShippingAdmin/Edit/{$shipping->id}">Edit</a>
						<span> / </span>
						<a href="/ShippingAdmin/Delete/{$shipping->id}" onclick="return confirm('Do you really want to delete shipping type; {$shipping->name}?')">Delete</a>
					</td>

				</tr>
			{/foreach}
			</tbody>
		</table>

		<p><a href="/ShippingAdmin/Edit/">New</a></p>
	</div>
</div>