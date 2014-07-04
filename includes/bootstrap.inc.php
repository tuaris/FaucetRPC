<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);
 
//require_once('FirePHPCore/fb.php');
@include_once("system/multisite.inc.php"); //Enables Multisite Capabilites
require_once("system/app.conf.php"); //Application Constants
@include_once("config/local.conf.php"); //User Made Configuration Options

//Autoload classes
spl_autoload_register(function ($class) {
	include 'classes/' . $class . '.class.php';
});

//Check if faucet RPC interface is configured
if(is_file(APPLICATION_CONFDIR . 'faucet.conf.php')) {
	include('includes/faucet.inc.php');
}
else {
	//No faucet RPC configuration found
	echo "Not Configured!\n";
	exit;
}

//Check if wallet RPC interface is configured
if(is_file(APPLICATION_CONFDIR . 'wallet.conf.php')) {
	include('includes/wallet.inc.php');
}
else {
	//No wallet configuration found
	echo "Not Configured!\n";
	exit;
}


?>