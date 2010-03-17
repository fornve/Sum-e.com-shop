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
	<link href="/resources/css/blueprint.css" rel="stylesheet" type="text/css" media="screen" />
	<link href="/resources/css/default.css" rel="stylesheet" type="text/css" media="screen" />
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
</head>
<body>

	<div onclick="$('#user_notification').fadeOut('slow')" class="user_notification" id="user_notification" title="User Notification" style="display: none;">
		<div id="user_notification_message"></div>
	</div>

<div id="wrapper">

	<div id="header">
		<div class="logo"><a href="/" title="Shop home page">Shop - Carp 4 </a></div>
		<div class="shop_description">
			<em> template design by <a href="http://sum-e.com" title="Sum-e.com">Sum-e.com</a>
			<br />
			inspired by <a href="http://www.freecsstemplates.org/">Free CSS Templates</a></em>
		</div>

		<div id="search">
			<form method="get" action="/Search/Results/">
				<fieldset>
				<input type="text" name="q" id="search-text" size="15" />
				<input type="image" src="/resources/icons/mint/search.png" id="search-submit" alt="Search" value="Search" />
				</fieldset>
			</form>
		</div>
		<!-- end #search -->
	</div>
	<!-- end #header -->
	<div id="subheader">
		<div id="menu">
			<ul>
				<li><a href="/" title="Shop homepage">Home</a></li>
				<li><a href="/Search/Advanced" title="Advanced search">Search</a></li>
				<li><a href="/Blog/" title="Shop blog">Blog</a></li>
				{if $smarty.session.admin}
					<li><a href="/Admin/" titl="Shop administration">Admin</a></li>
				{/if}
				<li><a href="/Page/View/10/Links" title="Shop links">Links</a></li>
				<li><a href="/TermsAndConditions" title="Terms and Conditions">Tnc</a></li>
				<li><a href="/Contactus/" title="Contact us">Contact</a></li>
			</ul>
		</div>
		<!-- end #menu -->

        <div id="basket">
			{include file="basket/mini.tpl"}
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
		<ul>
			{if $smarty.const.SHOP_OPEN || $smarty.session.admin}
			<li>
				<h2>Categories</h2>
				<ul id="category_menu">
					{include file="category/menu.tpl"}
                </ul>
			</li>
			{/if}

            {if $smarty.session.admin}
			<li>
				<h2>Shop admin</h2>
				<ul class="collapsable">
                    <li class="main">
                        <strong onclick="jQuery('#products_menu').toggle('fast')">Products</strong>
                        <ul id="products_menu" {if $breadcrumbs.0.name=="Product Admin"}style="display: block;"{/if}>
                            <li><a href="/ProductAdmin/Edit">New</a></li>
                            <li><a href="/ProductAdmin/ProductList">List</a></li>
                        </ul>
                    </li>

                    <li><strong onclick="jQuery('#categories_menu').toggle('fast')">Categories</strong>
                        <ul id="categories_menu" {if $breadcrumbs.0.name=="Category Admin"}style="display: block;"{/if}>
                            <li><a href="/CategoryAdmin/Edit">New</a></li>
                            <li>List: Use 'Category menu'</li>
                        </ul>
                    </li>

                    <li><strong onclick="jQuery('#orders_menu').toggle('fast')">Orders / Sales</strong>
                        <ul id="orders_menu" {if $breadcrumbs.0.name=="Order Admin"}style="display: block;"{/if}>
                            <li><a href="/OrderAdmin/">List</a></li>
                            <li><a href="/OrderAdmin/DailyReport/">Daily report</a></li>
                            <li><a href="/OrderAdmin/FullReport/">Full report</a></li>
                        </ul>
                    </li>

                    <li><strong onclick="jQuery('#administrators_menu').toggle('fast')">Administrators</strong>
                        <ul id="administrators_menu" {if $breadcrumbs.0.name=="Shop Administrators"}style="display: block;"{/if}>
                            <li><a href="/Admin/Edit/">New</a></li>
                            <li><a href="/Admin/ListAll/">List</a></li>
                        </ul>
                    </li>

                    <li><strong onclick="jQuery('#shipping_menu').toggle('fast')">Shipping</strong>
                        <ul id="shipping_menu" {if $breadcrumbs.0.name=="Pages Admin"}style="display: block;"{/if}>
                            <li><a href="/ShippingAdmin/Edit/">New</a></li>
                            <li><a href="/ShippingAdmin/">List</a></li>
                        </ul>
                    </li>

                    <li><strong onclick="jQuery('#pages_menu').toggle('fast')">Pages</strong>
                        <ul id="pages_menu" {if $breadcrumbs.0.name=="Pages Admin"}style="display: block;"{/if}>
                            <li><a href="/PageAdmin/Edit/">New</a></li>
                            <li><a href="/PageAdmin/">List</a></li>
                        </ul>
                    </li>

                    <li><strong onclick="jQuery('#blog_menu').toggle('fast')">Blog</strong>
                        <ul id="blog_menu">
                            <li><a href="/BlogAdmin/Edit/">New</a></li>
                            <li><a href="/BlogAdmin/ListAll/">List</a></li>
                        </ul>
                    </li>

                    <li><strong onclick="jQuery('#other_menu').toggle('fast')">Other</strong>
                        <ul id="other_menu">
                            <li><a href="/SettingsAdmin/">Settings</a></li>
                            <li><a href="/SettingsAdmin/FlushCache/">Flush cache</a></li>
                        </ul>
                    </li>

                </ul>
			</li>
            {/if}

			<li>
				<h2>Documentation</h2>
				<ul>
					<li><a href="/Documentation/Db">DB</a></li>
					<li><a href="http://960.gs">Design model 960px</a></li>
					<li><a href="#">Velit semper nisi molestie</a></li>
					<li><a href="#">Eget tempor eget nonummy</a></li>
					<li><a href="#">Nec metus sed donec</a></li>
				</ul>
			</li>
		</ul>
	</div>
	<!-- end #sidebar -->
	<div style="clear: both;">&nbsp;</div>
</div>
<!-- end #page -->

<div id="footer">
	<p>Copyright (c) Copyright 2010 GIST Silversmiths</p>
	<p title="All these is only for development purpose">{if $smarty.session.admin}<a href="/Admin/Logout">Admin logout</a>{else}<a href="/Admin/Login">Admin login</a>{/if}
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
	{* <script type="text/javascript">
		var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");
		document.write(unescape("%3Cscript src='" + gaJsHost + "google-analytics.com/ga.js' type='text/javascript'%3E%3C/script%3E"));
	</script>
	<script type="text/javascript">
		try {
			var pageTracker = _gat._getTracker("UA-1892634-15");
			pageTracker._trackPageview();
		} catch(err) {}
	</script>*}
	{/literal}
</body>
</html>
