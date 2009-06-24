{literal}
<script type="text/javascript">
	function updatePaypalProPaymentForm()
	{
		cardtype = jQuery('#cardtype_select option:selected').html();
		if( cardtype == 'Maestro' || cardtype == 'Solo' )
		{
			jQuery('#startdate').show();
			jQuery('#issuenumber').show();
		}
		else
		{
			jQuery('#startdate').hide();
			jQuery('#issuenumber').hide();
		}
	}
</script>
{/literal}
<h3>Payment details</h3>
<table>

	<tr {if $error.creditcardtype} class="error"{/if}>
		<th>Card type</th>
		<td>
			<select name="creditcardtype" id="cardtype_select" onchange="updatePaypalProPaymentForm()">
				<option value="Visa"{if $payment_input->creditcardtype=='Visa'} selected="selected"{/if}>Visa</option>
				<option value="Maestro"{if $payment_input->creditcardtype=='Maestro'} selected="selected"{/if}>Maestro</option>
				<option value="MasterCard"{if $payment_input->creditcardtype=='MasterCard'} selected="selected"{/if}>MasterCard</option>
				<option value="Amex"{if $payment_input->creditcardtype=='Amex'} selected="selected"{/if}>Amex</option>
				<option value="Discover"{if $payment_input->creditcardtype=='Discover'} selected="selected"{/if}>Discover</option>
				<option value="Solo"{if $payment_input->creditcardtype=='Discover'} selected="selected"{/if}>Solo</option>
			</select>
			{if $error.creditcardtype}<div style="error_form_message">{$error.creditcardtype}</div>{/if}
		</td>
	</tr>


	<tr {if $error.acct} class="error"{/if}>
		<th>Credit card number</th>
		<td>
			<input type="text" name="acct" class="text" value="{$payment_input->acct}" maxlength="16" />
			{if $error.acct}<div style="error_form_message">{$error.acct}</div>{/if}
		</td>
	</tr>

	<tr {if $error.startdate} class="error"{/if} {if $payment_input->creditcardtype=='Maestro' || $payment_input->creditcardtype=='Solo'}{else}style="display: none;"{/if} id="startdate">
		<th>Start date</th>
		<td>
			{html_select_date prefix="startdate_" end_year="+9" display_days=false time=$startdate}
			{if $error.startdate}<div style="error_form_message">{$error.startdate}</div>{/if}
		</td>
	</tr>

	<tr {if $error.expdate} class="error"{/if}>
		<th>Expiry date</th>
		<td>
			{html_select_date prefix="expdate_" end_year="+9" display_days=false time=$expdate}
			{if $error.expdate}<div style="error_form_message">{$error.expdate}</div>{/if}
		</td>
	</tr>


	<tr {if $error.cvv2} class="error"{/if}>
		<th>Security number</th>
		<td>
			<input type="text" name="cvv2" class="text" value="{$payment_input->cvv2}" style="width: 30px" maxlength="3" />
			{if $error.cvv2}<div style="error_form_message">{$error.cvv2}</div>{/if}
		</td>
	</tr>

	<tr {if $error.issuenumber} class="error"{/if} {if $payment_input->creditcardtype=='Maestro' || $payment_input->creditcardtype=='Solo'}{else}style="display: none;"{/if} id="issuenumber">
		<th>Issue number</th>
		<td>
			<input type="text" name="issuenumber" class="text" value="{$payment_input->issuenumber}" />
			{if $error.issuenumber}<div style="error_form_message">{$error.issuenumber}</div>{/if}
		</td>
	</tr>

</table>
