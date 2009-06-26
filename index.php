<?php

require_once( 'config/config.php' );

session_start();
$www = new IndexController();
$www->Dispatch();
