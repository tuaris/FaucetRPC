<?php
define("FAUCET_RPC_USER", "admin");
define("FAUCET_RPC_PASS", "1234567890");
define("FAUCET_RPC_HOST", "remote.server.tld"); //Add port here if needed "host:port" if other than 80
define("FAUCET_RPC_PATH", "coin/rpc.php"); //no preceding '/'

//Minumim Payout (Not impliemnted)
define("FAUCET_MIN_PAYOUT", 1.00);

define("FAUCET_AUTORELOAD_ENABLE", true);  //Enable auto reload of the faucet balance
define("FAUCET_RELOAD_THRESHOLD", 10);  //Reload the faucet balance if it drops under this amount
define("FAUCET_RELOAD_AMOUNT", 5);  //Reload the faucet balance by this amount
define("FAUCET_INITIAL_AMOUNT", 15);  //If the faucet balance is 0, reload by this amount
?>