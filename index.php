<?php

require_once( 'config/config.php' );

function __autoload( $name )
{
	if( file_exists( 'class/'. $name .'.class.php' ) )
	{
		include_once( 'class/'. $name .'.class.php' );
	}
	elseif( file_exists( 'entity/'. $name .'.class.php' ) )
	{
		include_once( 'entity/'. $name .'.class.php' );
	}
	elseif( file_exists( 'controllers/'. $name .'.class.php' ) )
	{
		include_once( 'controllers/'. $name .'.class.php' );
	}
}

session_start();
$www = new IndexController();
$www->Dispatch();
