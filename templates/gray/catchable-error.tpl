<div class="post">

	<h2 class="title">Website error.</h2>
	
	<div class="post_content">

		<p>We are experiencing problems at the moment. Full error message has been sent to our developers and it will be resolved as soon as is possible.</p>
	
		<p>We are sorry for any inconvenience.</p>

		{if !$smarty.const.PRODUCTION}<div>{$error}</div>
			{if $e->query}<div>{$e->query|print_r}</div>{/if}
			{if $e->arguments}<div>{$e->arguments|var_dump}</div>{/if}
			<div>Trace</div>
			<div>{$e->getTrace()|var_dump}</div>
		{/if}
	</div>
</div>
<div class="post_close"></div>
