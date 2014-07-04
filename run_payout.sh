#!/bin/csh

set coin = $1
set PHP = /usr/local/bin/php

echo "Paying Out ${coin}"
${PHP} payer.php -confdir=${coin}
echo ""