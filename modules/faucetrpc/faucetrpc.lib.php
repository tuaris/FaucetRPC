<?php
// Facuet RPC Function Library

/* 
* Sends each payment one transaction at a time
* Returns an array of failed transactions
*/
function faucetrpc_send_one_by_one(&$FAUCET_RPC, &$WALLET_RPC, $pendingTx){
	$failedTx = [];
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

	return $failedTx;
}

/* 
* Sends all payments in one transaction
* Returns an array of failed transactions
*/
function faucetrpc_send_all_at_once(&$FAUCET_RPC, &$WALLET_RPC, $pendingTx){
	//To hold grouped payments info
	$groupedTx = array(); //for traking purposes
	$groupTx = array(); //submitted to the 'sendmany' function
	$totalPayment = 0; //Keep a running total

	$failedTx = [];

	// Group all payments
	foreach($pendingTx as $tx){
		echo "Adding Payment: To {$tx['payout_address']} Amount: {$tx['payout_amount']}\n";

		//Check address
		$addressvalid = @$WALLET_RPC->validateaddress($tx['payout_address']);
		if (!$addressvalid['isvalid']){
			echo "Bad Address {$tx['payout_address']}\n";
			$failedTx[] = $tx;
			continue;
		}

		// Group the transactions
		$groupedTx[] = $tx;
		@$groupTx[(string)$tx['payout_address']] += (float) $tx['payout_amount'];

		// Add to total
		$totalPayment = $totalPayment + (float) $tx['payout_amount'];
	}
	
	//If the total is equal to or larger than the minimum payout, send the money
	echo "Total Pending Amount: $totalPayment\n";
	if ($totalPayment >= FAUCET_MIN_PAYOUT){
		//Send Money
		echo "Minimum payment threshold satisfied\n";

		try{
			if(TEST_MODE){
				$txid = "a0123457890abcdefghijklmnopqrstuvwxyz100000TestTransaction0000001";
			}
			else{
				$txid = @$WALLET_RPC->sendmany('', $groupTx);
			}
			echo "Group payment sent: $txid\n";
		}
		catch(Exception $e){
			echo "Could not send group payment.\n";
			$failedTx[] = $tx;
		}

		// Mark all paid
		foreach($groupedTx as $tx){
			echo "Marking as paid, address: {$tx['payout_address']}\n";
			try{
				if(!TEST_MODE){
					$FAUCET_RPC->setPaidTx($tx['id'], $txid);
					//Reduce Load on the server
					sleep(SLEEP_TIME);
				}
			}
			catch(Exception $e){
				$failedTx[] = $tx;
				echo "WARNING: Could not mark address as paid: {$tx['payout_address']}.  Double payment alert!\n";
			}
		}
	}
	else{
		echo "Minimum payment of '" . FAUCET_MIN_PAYOUT . "' not reached.\n";
	}

	return $failedTx;
}
?>
