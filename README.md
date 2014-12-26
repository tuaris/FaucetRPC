FaucetRPC
=========

This is a a remote control and payout script for the MultiFaucet PHP script.  Still in it's early stages but fully functional for paying out and refilling the faucet via RPC.

- Copy sample-faucet.conf.php and sample-wallet.conf.php as faucet.conf.php and wallet.conf.php.
- Edit each file (look at notes inside the sample files)
- Setup your Cron job (see Crontab.txt for an example)
- Do a test run

Minumim Payout/Transaction Grouping
-----------------------------------

Transaction grouping allows you send send all pending payments in a single transaction thus allowing you to reduce transaction fees. 

When enabled, if the sum of pending payments is less than 'FAUCET_MIN_PAYOUT', payments will not be sent in the current run. Set 'FAUCET_MIN_PAYOUT' to '0' to disable this feature and always use individual transactions.
