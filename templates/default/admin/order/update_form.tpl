<h3>Update order</h3>
<div style="border: 2px solid red; -moz-border-radius: 10px; padding: 10px 20px; width: 340px;">
	<form action="/OrderAdmin/Update/{$order->id}" method="post">
		<input type="hidden" name="previous_status" value="{$order->GetStatus()}" />
		<br />
		<label>Status:</label>
		<br />
		<select name="status">
			<option>Completed</option>
			<option>Cancelled</option>
		</select>

		<br />
		<label>Payer ID</label>
		<br />
		<input class="text" type="input" name="payer_id" value="" maxlength="255" />

		<br />
		<label>Transaction ID</label>
		<br />
		<input class="text" type="input" name="transaction_id" value="" maxlength="255" />

		<br />
		<input type="submit" value="Save" />
	</form>
</div>