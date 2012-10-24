<?php

/* Startup :fixme It should be in separate file */

error_reporting( E_ALL ^E_WARNING ^E_NOTICE );
ini_set('display_errors', 'On');

require_once( 'class/Config.class.php' );

Config::set( 'timer', microtime( true ) );
Config::set( 'project-path', substr( __file__, 0, strlen( __file__ ) - 18 ) );
Config::set( 'include-path', '/var/www/include' );
Config::set( 'production', false );

define( 'PRODUCTION', Config::_( 'production' ) ); // legacy

spl_autoload_register( 'autoload' );

function autoload( $name )
{
	$path_array = array(
		'class/',
		'entity/',
		'controllers/',
		Config::get( 'include-path' ) .'/class/'
	);

	foreach( $path_array as $path )
	{
		if( file_exists( $path . $name .'.class.php' ) )
		{
			include_once( $path . $name .'.class.php' );
			return true;
		}
	}
}

/* configuration begins */

Config::set( 'project-name', 'shop' );

Config::set( 'CACHE_TYPE', 'memcache' );
define( 'CACHE_HOST', '127.0.0.1' );
define( 'CACHE_PORT', 11211 );
define( 'CACHE_LIFETIME', 12000 ); // in seconds
define( 'CACHE_PREFIX', Config::_( 'project-name' ) );

define( 'PAGE_CACHE_DIR', '/tmp/shop/page_cache' ); // compiled pages cache

define( 'CURRENCY_SIGN', '$;' );
define( 'SALES_TAX_NAME', 'VAT' );
define( 'VAT_DISPLAY', false );

define( 'SMARTY_DIR', Config::get( 'include-path' ) .'/Smarty-3.0.6/libs/' );
define( 'SMARTY_TEMPLATES_DIR', Config::get( 'project-path' ) ."/templates/gray/" );
define( 'SMARTY_DEFAULT_TEMPLATES_DIR', Config::get( 'project-path' ) ."/templates/default/" );
define( 'PRODUCTION', false );

define( 'LOG_DIRECTORY', Config::get( 'project-path' ) .'/log' );

if( Config::get( 'production' ) )
{
	define( 'ADMIN_EMAIL', 'marek@dajnowski.net' );
	define( 'SMARTY_COMPILE_DIR', '/tmp/shop' );
	define( 'ASSETS_PATH', '/home/fornve/assets/shop' );
	define( 'PAYPAL_ACCOUNT_EMAIL', 'fornve@yahoo.co.uk' );
}
else
{
	define( 'ADMIN_EMAIL', 'tigi@sunforum.co.uk' );
	define( 'SMARTY_COMPILE_DIR', '/tmp/shop' );
	define( 'ASSETS_PATH', '/var/assets/shop' );
	define( 'PAYPAL_ACCOUNT_EMAIL', 'dummy_1244752696_biz@dajnowski.net' );
}

require_once( 'database.php' );

/* end of configuration */

if( !file_exists( Config::get( 'include-path' ) .'/class/Entity.class.php' ) )
{
	die('LiteEntityLib not found. Please install https://github.com/fornve/LiteEntityLib to '. Config::get( 'include-path' ) );
}

if( !file_exists( SMARTY_COMPILE_DIR ) )
{
	mkdir( SMARTY_COMPILE_DIR );
}

require_once( SMARTY_DIR .'Smarty.class.php' );


