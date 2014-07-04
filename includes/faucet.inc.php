<?php
//Connects to the MultiFaucet RPC Interface

//Load faucet config
include(APPLICATION_CONFDIR . 'faucet.conf.php');

//Faucet Communicator
$FAUCET_RPC = new jsonRPCClient(sprintf('http://%s:%s@%s/%s',
	FAUCET_RPC_USER,
	FAUCET_RPC_PASS,
	FAUCET_RPC_HOST,
	FAUCET_RPC_PATH
));
?>