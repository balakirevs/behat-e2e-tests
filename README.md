# E2E automation tests

Key components:
===============
#####Behat 3
#####Mink Extension
#####Mink Field Randomizer
#####PageObjects

SETUP
==============

From Project repo root folder run following commands:

```bash
$ curl http://getcomposer.org/installer | php
$ php composer.phar install
$ npm install -g phantomjs   # (brew install phantomjs or sudo apt-get install phantomjs)
$ wget http://selenium-release.storage.googleapis.com/2.53.1/selenium-server-standalone-2.53.1.jar
```
```bash
$ sudo apt-get install php5-imagick      # neccessary for the screenshots
$ sudo php5enmod imagick
$ sudo apt-get install ant               # to run tests in parallel
```

```bash
$ cd project-name
$ composer install
```

BROWSERS
==============
- FIREFOX (46.0.1)
  - __NOTE:__ for the latest firefox version install the latest selenium 3.x.x version with the geckodriver
- Chrome (latest)
- Safari (latest)


RUNNING SELENIUM BROWSER TESTS
==============================

Before running behat to test the feature files in features directory, ensure the following commands are executed :-

```bash
$ java -jar selenium-server-standalone-[version].jar
```

Command to execute with selenium 3 and geckodriver:

```bash
$ java -jar -Dwebdriver.gecko.driver=/path/to/geckodriver selenium-server-standalone-3.0.1.jar
```


To run tests (open another terminal window):

```bash
$ bin/behat 
$ bin/behat --tags @home --format pretty --format progress        # with the console logs output ###
$ bin/behat -p firefox
$ bin/behat -p chrome
$ bin/behat -p safari
$ bin/behat -p chrome_mobile
$ bin/behat --profile=preprod --tags @product                     # run with defenite profile configs ###
```

Second test runs using Guzzle (for API), the rest using Firefox

RUNNING PHANTOMJS TESTS (HEADLESSLY)
====================================

```bash
$ phantomjs --webdriver=4445
```

###### If the tests are run against https base url and running a self-signed certificate, start server with an additional --ignore-ssl-errors=true flag:

```bash
$ phantomjs --webdriver=4445 --ignore-ssl-errors=true
```

```bash
$ bin/behat -p phantomjs --tags @home --format pretty --format progress
```

PERFORMANCE/PARALLEL TESTING
============================

```bash
$ apt-get install parallel
$ apt-get install xvfb
$ java -jar selenium-server-standalone-2.53.1.jar --role hub
$ find features -iname '*.feature'|  parallel --gnu -j5 --group bin/behat -p chrome --tags @home --colors
```

```bash
$ ant run                         # run in parallel all targets
$ ant phantomjs                   # run in parallel one target
$ ant chrome firefox              # run in parallel several targets
```

CROSS BROWSER TESTING
===========================
* www.saucelabs.com
* www.browserstack.com

##To Run tests on Sauce Labs:  

You need to create account with SauceLabs https://saucelabs.com/signup 
Once registered you will have username and API key. 

Web-Driver (selenium2) Tests: 

update behat.yml and 'sauce' profile. 
Change "wd_host: username:apikey@ondemand.saucelabs.com/wd/hub" with your username and Password. 


And Run 

       $ bin/behat -p sauce
       $ bin/behat -c sauce.yml

You will see test running on SauceLabs https://saucelabs.com/jobs  


Selenium1 driver Tests: 

update behat.yml and 'sauce-rc' profile. 
"username":         "username",
"access-key":       "apikey", with your username and Password. 


And Run 

         $ bin/behat -p sauce-rc

You will see test running on SauceLabs https://saucelabs.com/jobs


REPORTING
============

As well as a html style report, there is a graphical report-based version using Twig if needed.
It can be installed providing additional library and settings.

Raports can be found in a report/ folder.
Raports are not generated when executing tests with options like "--format pretty --format progress".


SCREENSHOTS
============

Screenshots on the failing tests can be found in screenshot directory.

Integrating with CI
=============

Both CircleCI and Travis include PhantomJS, so there's no need to install or update. 

We just need to start the PhantomJS webdriver in the background. Then just run the Behat tests

### CircleCI
```
machine:
  php:
    version: 7.0.4

test:
  pre:
    - phantomjs --webdriver=4444:
        background: true
  override:
    - bin/behat -f junit -o $CIRCLE_TEST_REPORTS -p phantomjs -f pretty -f progress -o std
```

### TravisCI
```
language: php
php:
  - '7.0'

before_script:
  - composer install
  - "phantomjs --webdriver=4444 > /dev/null &"

script:
  - bin/behat -p phantomjs -f pretty -f progress
```