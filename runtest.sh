#!/bin/bash

rm testerEmail.txt
email=$1
echo $email >> testerEmail.txt
vendor/bin/phantomjs --webdriver=4444 &
vendor/bin/codecept clean
vendor/bin/codecept run acceptance CheckoutCest:testGuest  --steps
pkill phantomjs