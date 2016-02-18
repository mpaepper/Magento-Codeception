To set this up, download the Selenium jar file: http://goo.gl/qTy1IB
Run selenium in the console using: java -jar $selenium.jar where $selenium.jar is your downloaded jar file

Install codeception:
sudo curl -LsS http://codeception.com/codecept.phar -o /usr/local/bin/codecept
sudo chmod a+x /usr/local/bin/codecept

Then inside this repository, run: codecept run acceptance --steps

Alternative to using Firefox -> if you want to run in a headless mode, then
install phantomjs: download from http://phantomjs.org/download.html, extract and then run binary:

phantomjs --webdriver=4444 (quit the firefox selenium run before)

Then in the file tests/acceptance.suite.yml replace firefox with phantomjs and run as before :)
It runs a little bit faster, with less memory and no extra window opens.
When you have an error, you can still see a screenshot in the _output folder.
