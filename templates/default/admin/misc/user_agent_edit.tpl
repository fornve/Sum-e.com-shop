<div class="post basket">
	<h2 class="title">Edit user agent</h2>
	<div class="post_content">
		<h3>{$user_agent->name}</h3>
		<p>Visits <strong>{$user_agent->count+0}</strong> times.</p>

		<form method="post" action="/MiscAdmin/EditUserAgent/{$user_agent->id}">
		<table class="datatable">
			<tr class="header">
				<th>Agent</th>
				<th>&nbsp;</th>
			</tr>

			<tr>
				<td>
					<select name="agent" style="width: 100%">
						<option value=""></option>
						{foreach from=$agents item=types}
							<option value="{$types->agent}" {if $types->agent==$user_agent->agent}selected="selected"{/if}>{$types->agent}</option>
						{/foreach}
					</select>
				</td>
				<td>
					<input type="text" name="new_agent" value="" />
				</td>
			</tr>

			<tr class="header">
				<th>Type</th>
				<th>&nbsp;</th>
			</tr>

			<tr>
				<td>
					<select name="type" style="width: 100%">
						<option value=""></option>
						{foreach from=$agent_types item=types}
							<option value="{$types->type}" {if $types->type==$user_agent->type}selected="selected"{/if}>{$types->type}</option>
						{/foreach}
					</select>
				</td>
				<td>
					<input type="text" name="new_type" value="" />
				</td>
			</tr>

			<tr class="header">
				<th>Operational system (os)</th>
				<th>&nbsp;</th>
			</tr>

			<tr>
				<td>
					<select name="os" style="width: 100%">
						<option value=""></option>
						{foreach from=$oss item=types}
							<option value="{$types->os}" {if $types->os==$user_agent->os}selected="selected"{/if}>{$types->os}</option>
						{/foreach}
					</select>
				</td>
				<td>
					<input type="text" name="new_os" value="" />
				</td>
			</tr>



			<tr class="item">
				<td>select</td><td>or type new</td>
			</tr>

		</table>
		<input type="submit" value="Save" />
		</form>

	</div>
</div>