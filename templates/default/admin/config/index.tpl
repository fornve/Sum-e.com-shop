<div class="post">

	<div class="post_cell ui-widget ui-widget-content ui-corner-all">
		<h2 class="title">Shop Configuration</h2>
		<form action="/SettingsAdmin/" method="post">
			<table class="admin_table">
			{foreach from=$configs item=config}
				<tr>
					<td>{$config->title}</td>
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
								<img style="z-index: 0;" src="/resources/icons/silk/lightbulb_off.png" alt="help" onmouseover="jQuery('#config_description_{$config->id}').show();" onmouseout="jQuery('#config_description_{$config->id}').hide();" />
								<div id="config_description_{$config->id}" style="z-index: 1; display: none; width: 150px; position: absolute; top: 14px; left: 0; background-color: #F0E68C; border: 1px solid #FFD700; padding: 5px; color: #800000;" onmouseover="jQuery('#config_description_{$config->id}').show();" onmouseout="jQuery('#config_description_{$config->id}').hide();">
									{$config->description}
								</div>
							</div>
						{/if}
					</td>
				</tr>
			{/foreach}
			</table>

			<input type="submit" value="Save" class="submit ui-corner-all" style="font-size: 18px;" />

			<div style="clear: both;"></div>
		</form>
	</div>
</div>
