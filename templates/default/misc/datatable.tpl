{* This template draws table from result object *}
{assign var=object value=$data_objects.0}
{assign var=schema value=$object->GetSchema()}

<table class="datatable">
	<tr class="header">
	{foreach from=$schema item=column}
		<th>{$column}</th>	
	{/foreach}
	</tr>

	{foreach from=$data_objects item=row}
	<tr class="item">
		{foreach from=$schema item=element}
			<td>{$row->$element}</td>
		{/foreach}
	</tr>
	{/foreach}

</table>
