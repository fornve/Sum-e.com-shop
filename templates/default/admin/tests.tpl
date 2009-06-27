<div class="post">
	<h2 class="title">First time tests</h2>
	<div class="post_content">

		<h3>Basic tests.</h3>
		<table style="width: 600px;">
			<tr>
				<th style="width: 250px;">Database</th>
				<td style="background-color: green; width: 350px;">OK</td>
			</tr>
			<tr>
				<th>{$smarty.const.PROJECT_PATH}/assets directory</th>
				<td style="background-color: {if $assets_test}green{else}red{/if};">{if $assets_test}Writeable{else}Not writeable<br />(Ubuntu/Debian) sudo chown -R www-data:www-data {$smarty.const.PROJECT_PATH}/assets{/if}</td>
			</tr>
		</table>

		{if $assets_test}
			<h3>Create administrator account.</h3>
			{include file='misc/form.tpl'}
		{/if}
	</div>
</div>