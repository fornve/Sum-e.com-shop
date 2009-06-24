<?php

define( 'PRODUCTION', false );
define( 'PROJECT_PATH', substr( __file__, 0, strlen( __file__ ) - 18 ) );
define( 'PROJECT_NAME', 'shop' );

require_once( 'database.php' );

define( 'MEMCACHE_HOST', '127.0.0.1' );
define( 'MEMCACHE_PORT', 11211 );
define( 'MEMCACHE_LIFETIME', 12000 ); // in seconds
define( 'MEMCACHE_PREFIX', 'C4DEVELOPMENT' );

define( 'CURRENCY_SIGN', '&pound;' );

if( PRODUCTION )
{
	define( 'ADMIN_EMAIL', 'marek@dajnowski.net' );
	define( 'TIMER', microtime( true ) );
	define( 'SMARTY_COMPILE_DIR', '/tmp/shop' );
    require_once( PROJECT_PATH .'/smarty/Smarty.class.php' );
    define( 'SMARTY_DIR', PROJECT_PATH .'/smarty/' );
	define( 'ASSETS_PATH', '/home/fornve/assets/shop' );
	define( 'PAYPAL_ACCOUNT_EMAIL', 'fornve@yahoo.co.uk' );
}
else
{
	define( 'ADMIN_EMAIL', 'tigi@sunforum.co.uk' );
	define( 'TIMER', microtime( true ) );
	define( 'SMARTY_COMPILE_DIR', '/tmp/shop' );
    define( 'SMARTY_DIR',  '/var/www/anadvert/smarty/' );
	
	require_once( '/var/www/anadvert/smarty/Smarty.class.php' );
	define( 'ASSETS_PATH', '/home/tigi/media/assets/shop' );
	define( 'PAYPAL_ACCOUNT_EMAIL', 'dummy_1244752696_biz@dajnowski.net' );
}

require_once( '/var/www/anadvert/class/Entity.class.php' );
