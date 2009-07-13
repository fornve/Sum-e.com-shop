<?php
#error_reporting( E_ALL ^E_WARNING ^E_NOTICE );
error_reporting( E_ALL );
define( 'TIMER', microtime( true ) );
define( 'PROJECT_PATH', substr( __file__, 0, strlen( __file__ ) - 18 ) );

/* configuration begins */

define( 'PROJECT_NAME', 'shop' );

define( 'CACHE_TYPE', 'memcache' );
define( 'MEMCACHE_HOST', '127.0.0.1' );
define( 'MEMCACHE_PORT', 11211 );
define( 'MEMCACHE_LIFETIME', 12000 ); // in seconds
define( 'MEMCACHE_PREFIX', 'C4DEVELOPMENT' );

define( 'CURRENCY_SIGN', '&pound;' );

define( 'INCLUDE_PATH', '/var/www/include/' );
define( 'SMARTY_DIR', INCLUDE_PATH .'smarty/' );
define( 'SMARTY_TEMPLATES_DIR', PROJECT_PATH ."/templates/gray/" );
define( 'PRODUCTION', false );

if( PRODUCTION )
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
	define( 'ASSETS_PATH', '/home/tigi/media/assets/shop' );
	define( 'PAYPAL_ACCOUNT_EMAIL', 'dummy_1244752696_biz@dajnowski.net' );
}
require_once( 'database.php' );

/* end of configuration */

if( !file_exists( INCLUDE_PATH .'/class/Entity.class.php' ) )
{
	die('LiteEntityLib not found, please follow <a href="http://www.sum-e.com/Page/Installation/#LiteEntityLib">instructions</a> to install it.');
}
#elseif( !file_exists( INCLUDE_PATH .'/smarty/Smarty.class.php' ) )
#{
#	die('Smarty not found, please follow <a href="http://www.sum-e.com/Page/Installation/#Smarty">instructions</a> to install it.');
#}


if( !file_exists( SMARTY_COMPILE_DIR ) )
	mkdir( SMARTY_COMPILE_DIR );

require_once( SMARTY_DIR .'Smarty.class.php' );

function __autoload( $name )
{
	$path_array = array( 'class/', 'entity/', 'controllers/', INCLUDE_PATH .'class/' );

	foreach( $path_array as $path )
	{
		if( file_exists( $path . $name .'.class.php' ) )
		{
			include_once( $path . $name .'.class.php' );
			return true;
		}
	}
}

