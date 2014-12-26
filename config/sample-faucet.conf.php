<?php
define("FAUCET_RPC_USER", "admin");
define("FAUCET_RPC_PASS", "1234567890");
define("FAUCET_RPC_HOST", "remote.server.tld"); //Add port here if needed "host:port" if other than 80
define("FAUCET_RPC_PATH", "coin/rpc.php"); //no preceding '/'

/*
* Minumim Payout/Transaction Grouping (FAUCET_MIN_PAYOUT)
*
* Transaction grouping allows you send send all pending payments in a single transaction
* thus allowing you to reduce transaction fees. 
*
* When enabled, if the sum of pending payments is less than the threshold below
* payments will not be sent in the current run.
* 
* Set the value to '0' to disable this feature and always use individual transactions.
*/
define("FAUCET_MIN_PAYOUT", 1.00);

define("FAUCET_AUTORELOAD_ENABLE", true);  //Enable auto reload of the faucet balance
define("FAUCET_RELOAD_THRESHOLD", 10);  //Reload the faucet balance if it drops under this amount
define("FAUCET_RELOAD_AMOUNT", 5);  //Reload the faucet balance by this amount
define("FAUCET_INITIAL_AMOUNT", 15);  //If the faucet balance is 0, reload by this amount
?>