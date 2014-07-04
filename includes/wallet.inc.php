<?php
//Connects to the coin network

//Load coin config
include(APPLICATION_CONFDIR . 'wallet.conf.php');

//Coin Deamon Communicator
$WALLET_RPC = new jsonRPCClient(sprintf('http://%s:%s@%s:%s/',
	PAYMENT_GW_RPC_USER,
	PAYMENT_GW_RPC_PASS,
	PAYMENT_GW_RPC_HOST,
	PAYMENT_GW_RPC_PORT
));
?>