{assign var=min value=$pager.offset-5}

<ul class="pager">
		<li><strong>Page: {$pager.offset} of {$pager.max}</strong></li>
{if $pager.max > 1}
		{if $pager.max > 1 &&  $pager.offset > 1}
			<li><a href="{$pager.self}/1/{$pager.option}">First</a> &lt;&lt; </li>
		{else}
			<li>First &lt;&lt; </li>
		{/if}
		{if $pager.offset gt 1}
			<li><a href="{$pager.self}/{$pager.offset-1}/{$pager.option}">Previous</a> &lt;
		{else}
			<li>Previous &lt;
		{/if}
	{if $min lt 1}
		{section name=pagerloop loop=11 max=$pager.max start=1}
			{if 1 neq $smarty.section.pagerloop.index} | {/if}
			{if $pager.offset neq $smarty.section.pagerloop.index}
				<li><a href="{$pager.self}/{$smarty.section.pagerloop.index}/{$pager.option}">{$smarty.section.pagerloop.index}</a>
			{else}
				<li><strong>{$smarty.section.pagerloop.index}</strong></li>
			{/if}
		{/section}
	{else}
		{section name=pagerloop loop=$max+1 start=$min max=11}
			{if $min neq $smarty.section.pagerloop.index} | {/if}
			{if $pager.offset neq $smarty.section.pagerloop.index}
				<li><a href="{$self}/{$smarty.section.pagerloop.index}/{$pager.option}">{$smarty.section.pagerloop.index}</a>
			{else}
				<li><strong>{$smarty.section.pagerloop.index}</strong></li>
			{/if}
		{/section}
	{/if}
		{if $pager.offset lt $pager.max}
		&gt; <li><a href="{$pager.self}/{$pager.offset+1}{$pager.order}">Next</a></li>
			{if $pager.max gt 1}
				<li> &gt;&gt; <a href="{$pager.self}/{$pager.max}/{$pager.option}">Last</a></li>
			{/if}
		{else}
		&gt; <li>Next</li>
			{if $pager.max gt 1}
				<li>&gt;&gt; Last</li>
			{/if}
		{/if}
{/if}
</ul>