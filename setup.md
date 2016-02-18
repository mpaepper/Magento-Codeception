To set this up, download the Selenium jar file: http://goo.gl/qTy1IB
Run selenium in the console using: java -jar $selenium.jar where $selenium.jar is your downloaded jar file

Install codeception:
sudo curl -LsS http://codeception.com/codecept.phar -o /usr/local/bin/codecept
sudo chmod a+x /usr/local/bin/codecept

Then inside this repository, run: codecept run acceptance --steps
