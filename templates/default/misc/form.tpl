{strip}
<form method="{$form->method}" action="{$form->action}"{if $form->file_upload} enctype="multipart/data"{/if}>
	<table>
		{foreach from=$form->fields key=name item=field}
		<tr{if $field->error|@count} class="error"{/if}>
			<th><label for="{$name}">{$field->label}</label></th>
			<td>
				{if $field->type == 'password'}
					<input type="password" id="{$name}" name="{$name}"
						{if $field->class} class="{$field->class}"{/if}
						{if $field->onclick} class="{$field->onclick}"{/if}
					/>
				{elseif $field->type == 'select'}
					<select name="{$name}">
						{foreach from=$field->options item=option key=value}
							<option
								{if $value} value="{$value}"
								{if $value==$field->value} selected="selected"{/if}
									{else} value="{$option}"{if $option==$input->$name} selected="selected"{/if}
								{/if}
									>{$option}</option>
						{/foreach}
					</select>
				{elseif $field->type == 'select_country'}
					<select name="{$name}">
						{foreach from=$field->options item=country}
							<option value="{$country->code}"
								{if $country->code==$field->value} selected="selected"{/if}
							>{$country->name}</option>
						{/foreach}
					</select>
				{else}
					<input type="text" id="{$name}" name="{$name}"
						{if $field->class} class="{$field->class}"{/if}
						value="{if $field->value}{$field->value}{/if}"
						{if $field->onclick} class="{$field->onclick}"{/if}
					/>
				{/if}
			</td>
			<td>
				{foreach from=$field->error item=error name=error_loop}
					{$error}{if !$smarty.foreach.error_loop.last}<br />{/if}
				{/foreach}
			</td>
		</tr>

		{/foreach}
		<tr>
			<td>
				{if $form->submit.type == 'image'}
					<input type="image" src="{$form->submit.src}" alt="{$form->submit.value}"{if $form->submit.class} class="{$form->submit.class}"{/if} />
				{else}
					<input type="submit" value="{$form->submit.value}"{if $form->submit.class} class="{$form->submit.class}"{/if} />
				{/if}
			</td>
		</tr>
	</table>
</form>
{/strip}