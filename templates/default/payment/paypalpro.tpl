{literal}
<style type="text/css">
.paypalpro input.text			{ width: 180px; }
.paypalpro select.countries		{ width: 160px; }
.paypalpro td.block				{ vertical-align: top;  }
</style>
{/literal}

<div class="post">
	<h2 class="title">Please enter your payment details</h2>

	<div class="post_content">
		<form method="post" action="/Payment/Paypalpro/" class="paypalpro">
			<table>
				<tr>
					<td valign="top" class="block">
						{* Personal details *}
						{include file="payment/paypalpro/personal_details.tpl"}

						{* Billing details *}
						{include file="payment/paypalpro/billing_address.tpl"}
					</td>

					<td valign="top" class="block">
						{* Payment details *}
						{include file="payment/paypalpro/payment_details.tpl"}
						
						<div>
							<input type="submit" value="Pay" onclick="jQuery(this).attr('disabled','true')" />
						</div>
					</td>
				</tr>
			</table>

		</form>
	</div>
</div>
