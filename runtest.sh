#!/bin/bash
rm nohup.out
nohup vendor/bin/phantomjs --webdriver=4444 &
vendor/bin/codecept run acceptance --steps
pkill phantomjs