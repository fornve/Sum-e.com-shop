<h3>Billing address</h3>
<table>
	<tr {if $error.street} class="error"{/if}>
		<th>House number and Street</th>
		<td>
			<input type="text" name="street" class="text" value="{$payment_input->street}" />
			{if $error.street}<div style="error_form_message">{$error.street}</div>{/if}
		</td>
	</tr>

	<tr {if $error.city} class="error"{/if}>
		<th>City</th>
		<td>
			<input type="text" name="city" class="text" value="{$payment_input->city}" />
			{if $error.city}<div style="error_form_message">{$error.city}</div>{/if}
		</td>
	</tr>

	<tr {if $error.country} class="error"{/if}>
		<th>Country</th>
		<td>
			{if $payment_input->country}<img src="/resources/icons/flag/{$payment_input->country|lower}.png" alt="{$payment_input->country}" />{/if}
			<select name="country" class="countries">
				<option value="">-- Please select --</option>
				{foreach from=$countries item=country}
					<option value="{$country->code}"{if $country->code==$payment_input->country} selected="selected"{/if} style="background: #fff url('/resources/icons/flag/{$country->code|lower}.png') center left no-repeat; padding-left: 20px;">{$country->name}</option>
				{/foreach}
			</select>
			{if $error.country}<div style="error_form_message">{$error.country}</div>{/if}
		</td>
	</tr>

	<tr {if $error.zip} class="error"{/if}>
		<th>Postcode</th>
		<td>
			<input type="text" name="zip" class="text" value="{$payment_input->zip}" />
			{if $error.zip}<div style="error_form_message">{$error.zip}</div>{/if}
		</td>
	</tr>

</table>
