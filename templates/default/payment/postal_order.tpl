{literal}
<style type="text/css">

.order_form table		{ border: 2px solid #434343; -moz-border-radius: 10px; margin: 0 30px; width: 450px; }
.order_form h3			{ padding: 15px 0; }
.reauired				{ color: red; }
.order_form .error td	{ background-color: #FFDEAD;' }
</style>
{/literal}

<div class="post order_form">

	<h2 class="title">Postal Order</h2>

	<div class="post_content">
		<h3>Please give your details where your order will be delivered to</h3>

		<form action="/Payment/PostalOrder" method="post">
			<table>

				<tr {if $error.firstname}class="error"{/if}>
					<td><label for="firstname">Firstname</label><span class="required">*</span></td>
					<td><input type="text" class="text" name="firstname" id="firstname" value="{$smarty.post.firstname}" /></td>
				</tr>

				<tr {if $error.lastname}class="error"{/if}>
					<td><label for="lastname">Lastname</label><span class="required">*</span></td>
					<td><input type="text" class="text" name="lastname" id="lastname" value="{$smarty.post.lastname}" /></td>
				</tr>

				<tr {if $error.address}class="error"{/if}>
					<td><label for="address">Address</label><span class="required">*</span></td>
					<td><input type="text" class="text" name="address" id="address" value="{$smarty.post.address}" /></td>
				</tr>

				<tr {if $error.postcode}class="error"{/if}>
					<td><label for="postcode">Postcode</label><span class="required">*</span></td>
					<td><input type="text" class="text" name="postcode" id="postcode" value="{$smarty.post.postcode}" /></td>
				</tr>

				<tr {if $error.city}class="error"{/if}>
					<td><label for="city">City</label><span class="required">*</span></td>
					<td><input type="text" class="text" name="city" id="city" value="{$smarty.post.city}" /></td>
				</tr>

				<tr>
					<td><label for="country">Country</label><span class="required">*</span></td>
					<td>
						<select name="country">
							{foreach from=$countries item=country}
								<option value="{$country->code}" {if !$smarty.post.country && $country->code=='GB'}selected="selected"{elseif $smarty.post.country == $country->code}selected="selected"{/if}>{$country->name}</option>
							{/foreach}
						</select>
					</td>
				</tr>

				<tr {if $error.tnc}class="error"{/if}>
					<td colspan="2"><label for="tnc">I read and understand <a href="/Page/TnC" target="_blank">Terms and Conditions</a></label><span class="required">*</span>
					<input type="checkbox" class="text" name="tnc" id="tnc" value="accepted" {if $smarty.post.tnc}checked="checked"{/if} /></td>
				</tr>

			</table>

			<h3>Contact details</h3>

			<table>

				<tr {if $error.firstname}class="error"{/if}>
					<td><label for="email">Email</label><span class="required">*</span></td>
					<td><input type="text" class="text" name="email" id="email" value="{$smarty.post.email}" /></td>
				</tr>

				<tr {if $error.phone}class="error"{/if}>
					<td><label for="p">Phone</label></td>
					<td><input type="text" class="text" name="phone" id="phone" value="{$smarty.post.phone}" /></td>
				</tr>


				<tr>
					<td colspan="2">
						<input type="submit" value="Submit" />
					</td>
				</tr>

			</table>

			<div style="margin-top: 10px;">
				Fields marked <span class="required">*</span> are required.
			</div>
		</form>
	</div>
</div>
