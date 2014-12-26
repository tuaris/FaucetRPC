<?php include('includes/bootstrap.inc.php'); ?>
<?php include('modules/faucetrpc/faucetrpc.lib.php'); ?>
<?php
if(TEST_MODE){echo "!!!TEST MODE!!!\n";}

//To hold pending and failed transactions
$pendingTx = array();
$failedTx = array();

//Attempt to load pending transactions from MultiFaucet server
try{
	$pendingTx = $FAUCET_RPC->getPendingTx();
}
catch(Exception $e){
	//TODO: Fix this so an exception does not get thrown if the transaftions are empty
	echo "No Pending Transacations\n";
}

// If a minimum payout is set, use the group payment option
if(FAUCET_MIN_PAYOUT > 0){
	echo "Using group payment option\n";
	$failedTx = faucetrpc_send_all_at_once($FAUCET_RPC, $WALLET_RPC, $pendingTx);
}
else{
	echo "Using individual payment option\n";
	$failedTx = faucetrpc_send_one_by_one($FAUCET_RPC, $WALLET_RPC, $pendingTx);
}

//Cleanup Failed Transactions
foreach($failedTx as $tx){
	echo "Removing failed address: {$tx['payout_address']}\n";
	try{
		if(!TEST_MODE){
			$FAUCET_RPC->setPaidTx($tx['id'], "FAILED");
			//Reduce Load on the server
			sleep(SLEEP_TIME);
		}
	}
	catch(Exception $e){
		echo "Could not clean failed address: {$tx['payout_address']}\n";
	}
}

//Reload Faucet Balance
$balance = 0;

//First get the balance
try{
	$balance = $FAUCET_RPC->getFunds();
	echo "\nFaucet Balance: $balance\n";
}
catch(Exception $e){
	//It's a zero balance if there is an error
	//TODO: Make this better!!!!
	echo "\nFaucet is empty.\n";
}

// Check if balance is below threshold and auto reload is enabled
if(FAUCET_AUTORELOAD_ENABLE && ($balance < FAUCET_RELOAD_THRESHOLD)){
	//Initial Load Required
	if($balance <= 0){
		$reload_amount = FAUCET_INITIAL_AMOUNT;
	}
	//Standard Reload
	else{
		$reload_amount = FAUCET_RELOAD_AMOUNT;
	}

	//Attempt to reload the faucet
	try{
		echo "\nReloading Faucet Balance by: $reload_amount\n";
		if(!TEST_MODE){
			$new_balance = $FAUCET_RPC->addFunds($reload_amount);
		}
		else{
			$new_balance = $balance + $reload_amount;
		}
		echo "\nFaucet Balance: $new_balance\n";
	}
	catch(Exception $e){
		echo "Could not fund faucet\n";
	}
}


?>