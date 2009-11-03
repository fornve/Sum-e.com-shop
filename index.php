<?php
require_once( 'config/config.php' );

if( !PRODUCTION )
{
	ini_set( 'display_errors', 'On' );
	ini_set( 'log_errors', 'Off' );
}

session_start();
$www = new IndexController();
$www->Dispatch();
