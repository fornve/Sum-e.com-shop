<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>{if $title}{$title} | {/if}Shop - Carp 4 </title>
    {if $metas|@count}
 	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
   {foreach from=$metas item=meta}
        <meta {if $meta.name} name="{$meta.name}"{/if} {if $meta.content}content="{$meta.content|truncate:250:''}"{/if} />
    {/foreach}
    {/if}
    <meta name="keywords" content="" />
    <meta name="description" content="" />
    <link href="/resources/css/blueprint.css" rel="stylesheet" type="text/css" media="screen" />
    <script type="text/javascript" src="/resources/js/jquery.js"></script>
		<script type="text/javascript" src="/resources/js/jquery-ui.js"></script>
		<link href="/resources/themes/ui-darkness/ui.all.css" rel="stylesheet" type="text/css" media="screen" />
    <script type="text/javascript" src="/resources/js/thickbox.js"></script>
    <script type="text/javascript" src="/resources/js/common.js"></script>

    {if $smarty.session.user_notification}
    <script type="text/javascript">
            $(document).ready(function(){ldelim}userNotification('{foreach from=$smarty.session.user_notification item=message}{assign var=notification_type value=$message.type}<p>{$message.text}</p>{/foreach}','{$notification_type}');{rdelim});

	</script>
    {/if}
{* literal}IE6 killer<style>*{position:relative}</style><table><input></table></style>{/literal*}
</head>
<body>

{$content}

</body>
</html>
