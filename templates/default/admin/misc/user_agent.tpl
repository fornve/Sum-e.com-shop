<div class="post basket">
	<h2 class="title">User agents (browsers)</h2>
	<div class="post_content">
		{assign var=data_objects value=$user_agents}
		{assign var=object value=$data_objects.0}
		{assign var=schema value=$object->GetSchema()}

		<table class="datatable">
			<tr class="header">
			{foreach from=$schema item=column}
				<th>{$column}</th>
			{/foreach}
				<th>&nbsp;</th>
			</tr>

			{foreach from=$data_objects item=row}
			<tr class="item">
				{foreach from=$schema item=element}
					<td>{$row->$element}</td>
				{/foreach}
					<td>
						<a href="/MiscAdmin/EditUserAgent/{$row->id}" title="Edit">
							Edit
						</a>
					</td>
			</tr>
			{/foreach}

		</table>

	</div>
</div>