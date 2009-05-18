
<div class="post">

	{assign var=checkout_stage value=3}
	{include file=checkout/checkout_stages.tpl}

	<h2 class="title">Your details</h2>

	<p>Please confirm your entries. You can correct these on their respective pages by clicking the order steps listed above. If all entries are correct, click "Next".</p>
	<form action="/Checkout/YourDetails" method="post">

		<table>

			<tbody>

				<tr>
					<td>Title</td>
					<td>
						<input class="checkout text" name="title" value="{$customer_details->title}" maxlength="16" />
					</td>
				</tr>

				<tr{if $error=='firstname'} class="error"{/if}>
					<td>
						<span>First name</span>
						<span style="color: red;">*</span>
					</td>
					<td>
						<input class="checkout text" name="firstname" value="{$customer_details->firstname}" maxlength="64" type="text" />
					</td>
				</tr>

				<tr{if $error=='lastname'} class="error"{/if}>
					<td>
						<span>Surname</span>
						<span style="color: red;">*</span>
					</td>
					<td>
						<input class="checkout text" name="lastname" value="{$customer_details->lastname}" maxlength="64" type="text" />
					</td>
				</tr>

				<tr{if $error=='address1'} class="error"{/if}>
					<td>
						<span>Address line 1</span>
						<span style="color: red;">*</span>
					</td>
					<td>
						<input class="checkout text" name="address1" value="{$customer_details->address1}" maxlength="100" type="text" />
					</td>
				</tr>

				<tr>
					<td>Address line 2</td>
					<td>
						<input class="checkout text" name="address2" value="{$customer_details->address2}" maxlength="100" type="text" />
					</td>
				</tr>

				<tr{if $error=='postcode'} class="error"{/if}>
					<td>
						<span>Postcode</span>
						<span style="color: red;">*</span>
					</td>
					<td>
						<input class="checkout text" name="postcode" value="{$customer_details->postcode}" maxlength="10" type="text" />
					</td>
				</tr>

				<tr{if $error=='city'} class="error"{/if}>
					<td>
						<span>City</span>
						<span style="color: red;">*</span>
					</td>
					<td>

						<input class="checkout text" name="city" value="{$customer_details->city}" maxlength="35" type="text" />
					</td>
				</tr>

				<tr{if $error=='country'} class="error"{/if}>
					<td>
						<span>Country</span>
						<span style="color: red;">*</span>
					</td>
					<td>{if $customer_details->country}<img src="http://sunforum.co.uk/resources/icons/flag/{$customer_details->country|lower}.png" alt="{$customer_details->country}" />{/if}
						<select name="country">
							<option value="">-- Please select --</option>
							{foreach from=$countries item=country}
								<option value="{$country->code}"{if $country->code==$customer_details->country} selected="selected"{/if} style="background: #fff url('http://sunforum.co.uk/resources/icons/flag/{$country->code|lower}.png') left center no-repeat; padding-left: 20px;">{$country->name}</option>
							{/foreach}
						</select>
					</td>
				</tr>

				<tr{if $error=='email'} class="error"{/if}>
					<td>
						<span>Email</span>
						<span style="color: red;">*</span>
					</td>
					<td>

						<input class="checkout text" name="email" value="{$customer_details->email}" maxlength="75" type="text" />
					</td>
				</tr>

				<tr{if $error=='confirm_email'} class="error"{/if}>
					<td>
						<span>Confirm e-mail</span>
						<span style="color: red;">*</span>
					</td>
					<td>
						<input class="checkout text" name="confirm_email" value="{$customer_details->confirm_email}" maxlength="75" type="text" />
					</td>
				</tr>

				<tr>
					<td>Phone</td>
					<td>
						<input class="checkout text" name="phone" value="{$customer_details->phone}" maxlength="75" type="text" />
					</td>
				</tr>

				<tr>
					<td colspan="2">
						<fieldset style="width: auto;">
							<legend>Note</legend>

							<textarea rows="10" cols="50" style="width: 100%;" name="note">{$customer_details->note}</textarea>
						</fieldset>
					</td>
				</tr>
				<tr{if $error=='tnc'} class="error"{/if}>
					<td></td>
					<td style="text-align: right; padding-right: 5px;">
						<input name="tnc" value="1" type="checkbox" /> I accept
						<a href="/Page/TnC" title="Terms and Conditions" target="_blank">Terms and Conditions</a>
					</td>
				</tr>
			</tbody>
		</table>

		<div style="float: right;">
			<input type="image" value="Next" alt="Next" src="/resources/images/button_next.png" />
		</div>
	</form>
</div>
