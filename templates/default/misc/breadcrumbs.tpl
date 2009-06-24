<ul>
	<li><a href="/" title="Home">Home</a></li>
	{foreach from=$breadcrumbs item=breadcrumb}
	<li> &gt; {if $breadcrumb.link}<a href="{$breadcrumb.link}" title="{$breadcrumb.name}">{$breadcrumb.name}</a>{else}{$breadcrumb.name}{/if}</li>
	{/foreach}
</ul>
