<div class="post basket">
	<h2 class="title">Main settings</h2>

	<form action="/SettingsAdmin/" method="post">
		<table>
		{foreach from=$configs item=config}
			<tr>
				<th>{$config->title}</th>
				<td>
					{if $config->type=='text'}
						<input type="text" name="config_{$config->id}" value="{$config->value}" maxlength="255" />
					{elseif $config->type=='boolean'}
						<select name="config_{$config->id}">
							<option value="0"{if $config->value=='0'} selected="selected"{/if}>No</option>
							<option value="1"{if $config->value=='1'} selected="selected"{/if}>Yes</option>
						</select>
					{else}
						{$config->value}
					{/if}
				</td>
				<td>
					{if $config->description}
						<div style="position: relative;">
							<img style="z-index: 0;" src="http://sunforum.co.uk/resources/icons/silk/lightbulb_off.png" alt="help" onmouseover="jQuery('#config_description_{$config->id}').show();" onmouseout="jQuery('#config_description_{$config->id}').hide();" />
							<div id="config_description_{$config->id}" style="z-index: 1; display: none; width: 150px; position: absolute; top: 14px; left: 0; background-color: #F0E68C; border: 1px solid #FFD700; padding: 5px; color: #800000;" onmouseover="jQuery('#config_description_{$config->id}').show();" onmouseout="jQuery('#config_description_{$config->id}').hide();">
								{$config->description}
							</div>
						</div>
					{/if}
				</td>
			</tr>
		{/foreach}
		</table>	<h2 class="title">Vendor settings</h2>

		<table>
		{foreach from=$vendor item=vendor_setting key=vendor_key}
			{if $vendor->InSchema($vendor_key) && $vendor_key!='id'}
				<tr>
					<th>{$vendor_key}</th>
					<td>
						{if $vendor_key!='country'}
							<input type="text" class="text" name="{$vendor_key}" value="{$vendor_setting}" maxlength="255" />
						{else}
							<select name="country">
								{foreach from=$countries item=country}
									<option value="{$country->code}" {if $country->code==$vendor_setting}selected="selected"{/if} style="background: #fff url('http://sunforum.co.uk/resources/icons/flag/{$country->code|lower}.png') left center no-repeat; padding-left: 20px;">{$country->name}</option>
								{/foreach}
							</select>
						{/if}
					</td>
				</tr>
			{/if}
		{/foreach}
		</table>

		<input type="image" src="/resources/images/button_save.png" alt="Save" value="Save" style="float: right;" />

		<div style="clear: both;"></div>
	</form>
	
</div>