<?php include('includes/bootstrap.inc.php'); ?>
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

//Send Payments
foreach($pendingTx as $tx){
	echo "Sending Payment to {$tx['payout_address']} Amount: {$tx['payout_amount']}\n";
	
	//Check address
	$addressvalid = @$WALLET_RPC->validateaddress($tx['payout_address']);
	if (!$addressvalid['isvalid']){
		echo "Bad Address {$tx['payout_address']}\n";
		$failedTx[] = $tx;
		continue;
	}

	//Send Money
	try{
		if(TEST_MODE){
			$txid = "a0123457890abcdefghijklmnopqrstuvwxyz100000TestTransaction0000001";
		}
		else{
			$txid = @$WALLET_RPC->sendtoaddress((string)$tx['payout_address'], (float) $tx['payout_amount']);
			$FAUCET_RPC->setPaidTx($tx['id'], $txid);
			//Reduce Load on the server
			sleep(SLEEP_TIME);
		}
	}
	catch(Exception $e){
		echo "Could not send Payment to {$tx['payout_address']}\n";
		$failedTx[] = $tx;
		continue;
	}
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