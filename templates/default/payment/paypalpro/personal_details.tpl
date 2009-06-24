<h3>Personal details</h3>
<table>
	<tr {if $error.firstname} class="error"{/if}>
		<th>Firstname</th>
		<td>
			<input type="text" name="firstname" class="text" value="{$payment_input->firstname}" />
			{if $error.firstname}<div style="error_form_message">{$error.firstname}</div>{/if}
		</td>
	</tr>
	
	<tr {if $error.lastname} class="error"{/if}>
		<th>Lastname</th>
		<td>
			<input type="text" name="lastname" class="text" value="{$payment_input->lastname}" />
			{if $error.lastname}<div style="error_form_message">{$error.lastname}</div>{/if}
		</td>
	</tr>
	
	<tr {if $error.phone} class="error"{/if}>
	
		<th>Phone</th>
		<td>
			<input type="text" name="phone" class="text" value="{$payment_input->phone}" />
			{if $error.phone}<div style="error_form_message">{$error.phone}</div>{/if}
		</td>
	</tr>
	
	<tr {if $error.email} class="error"{/if}>
	
		<th>Email address</th>
		<td>
			<input type="text" name="email" class="text" value="{$payment_input->email}" />
			{if $error.email}<div style="error_form_message">{$error.email}</div>{/if}
		</td>
	</tr>
	
</table>
