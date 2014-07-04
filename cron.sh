#!/bin/csh

# Set the prefered log directory
set LOG_DIR = /var/log/facuet

# Create Log Directory
mkdir -p ${LOG_DIR}

# List coins to run payout for
set coins = ""

foreach coin ($coins)
	./run_payout.sh ${coin} | ./timestamper >> ${LOG_DIR}/${coin}.log
end
