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
    <link href="/resources/css/gray.css" rel="stylesheet" type="text/css" media="screen" />
    <link href="/resources/css/print.css" rel="stylesheet" type="text/css" media="print" />
    <link href="/resources/css/thickbox.css" rel="stylesheet" type="text/css" media="screen" />

	<script type="text/javascript" src="/resources/js/jquery.js"></script>
	<script type="text/javascript" src="/resources/js/jquery-ui.js"></script>
	{if $smarty.session.admin}
		<link href="/resources/themes/ui-darkness/ui.all.css" rel="stylesheet" type="text/css" media="screen" />
	{/if}
	{if $smarty.session.admin && $smarty.const.TINY_MCE}
		<script type="text/javascript" src="/resources/js/tiny_mce/tiny_mce.js"></script>
	{/if}
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

	<div onclick="$('#user_notification').fadeOut('slow')" class="user_notification" id="user_notification" title="User Notification" style="display: none;">
		<div id="user_notification_message"></div>
	</div>

<div id="wrapper">

	<div id="header">
		<div class="logo">
			<a href="/" title="Shop home page">
				<img src="/resources/images/gray/logo.png">
			</a>
		</div>


		<div id="search">
			<form method="get" action="/Search/Results/">
				<fieldset>
				<input type="text" name="q" id="search-text" size="15" />
				<input type="image" src="http://sunforum.co.uk/resources/icons/mint/search.png" id="search-submit" alt="Search" value="Search" />
				</fieldset>
			</form>
		</div>

	</div>

	<!-- end #logo -->
<!-- end #header-wrapper -->

<div id="page">

    <div id="breadcrumbs">
		{include file='misc/breadcrumbs.tpl'}
    </div>

	<div id="content">
        {$content}
    </div>

	<!-- end #content -->
	<div id="sidebar">
        <div id="basket">&nbsp;
			{include file="basket/mini.tpl"}
        </div>

		<ul>
			<li>
				<ul>
					<li class="header"><h2>Menu</h2></li>
					<li><a href="/" title="Shop homepage">Home</a></li>
					<li><a href="/Search/Advanced" title="Advanced search">Search</a></li>
					{*<li><a href="/Blog/" title="Shop blog">Blog</a></li>*}
					{if $smarty.session.admin}
						<li><a href="/Admin/" titl="Shop administration">Admin</a></li>
					{/if}
					{*<li><a href="/Page/View/10/Links" title="Shop links">Links</a></li>*}
					<li><a href="/TermsAndConditions" title="Terms and Conditions">Tnc</a></li>
					<li><a href="/Contactus/" title="Contact us">Contact</a></li>
				</ul>
			</li>

			{if $smarty.const.SHOP_OPEN || $smarty.session.admin}
			<li>
				<ul id="category_menu">
					{include file="category/menu.tpl"}
                </ul>
			</li>
			{/if}

		</ul>
	</div>
	<!-- end #sidebar -->
	<div style="clear: both;">&nbsp;</div>
</div>
<!-- end #page -->

<div id="footer">
	<p>Copyright (c) 2009 Sum-e.com. All rights reserved. Developed by <a href="http://sum-e.com" title="Smashing e-commerce">sum-e.com</a> template design by <a href="http://sum-e.com" title="Sum-e.com">Sum-e.com</a><br />
    {if $smarty.session.admin}<a href="/Admin/Logout">Admin logout</a>{else}<a href="/Admin/Login">Admin login</a>{/if}
    [ Generated in: {$generated}s, db queries: <span onclick="$('#query_debug').show('fast');">{$entity_query|@count}] </span> | <span onclick="$('#basket_debug').show('fast');">Basket dump</span></div></p>
</div>
<!-- end #footer -->
</div>

    {if $entity_query}
    <div id="query_debug" style="display: none; position: fixed; bottom: 0; left: 0; padding: 5px; border: 1px solid gray; background-color: silver;">
            <span onclick="$('#query_debug').hide('fast')">Close</span>
            <ol style="">
                    {foreach from=$entity_query item=query}
                    <li style="font-size: 11px;background-color: mistyrose; margin: 2px 0; padding: 2px;">{$query}</li>
                    {/foreach}
            </ol>
            <span onclick="$('#query_debug').hide('fast')">Close</span>
    </div>
    {/if}

    {if $smarty.session.basket}
    <div id="basket_debug" style="display: none; position: fixed; bottom: 0; left: 0; padding: 5px; border: 1px solid gray; background-color: silver;">
        <span onclick="$('#query_debug').hide('fast')">Close</span>
            {include file="basket/basket_debug.tpl"}
        <span onclick="$('#query_debug').hide('fast')">Close</span>
    </div>
    {/if}

	{literal}
<script type="text/javascript">
var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");
document.write(unescape("%3Cscript src='" + gaJsHost + "google-analytics.com/ga.js' type='text/javascript'%3E%3C/script%3E"));
</script>
<script type="text/javascript">
try {
var pageTracker = _gat._getTracker("UA-1892634-15");
pageTracker._trackPageview();
} catch(err) {}</script>
	{/literal}
</body>
</html>
