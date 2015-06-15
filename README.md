Mink PhantomJS Driver
===========================
[![Build Status](https://travis-ci.org/jcalderonzumba/MinkPhantomJSDriver.svg?branch=master)](https://travis-ci.org/jcalderonzumba/MinkPhantomJSDriver)

Installation & Compatibility
----------------------------
You need a working installation of [PhantomJS](http://phantomjs.org/download.html)

This driver is tested using PhantomJS 1.9.8 but it should work with 1.9.X or latest 2.0.X versions

This driver supports **PHP 5.4 or greater**, there is NO support for PHP 5.3

Use [Composer](https://getcomposer.org/) to install all required PHP dependencies:

```bash
$ composer require --dev behat/mink jcalderonzumba/mink-phantomjs-driver
```

How to use
-------------
Extension configuration (for the moment NONE).
```yml
default:
  extensions:
    Behat\PhantomJSExtension:
```
Driver specific configuration:
```yml
Behat\MinkExtension:
phantomjs:
    phantom_server: "http://localhost:8510/api"
    template_cache: "/tmp/pjsdrivercache/phantomjs"
```
PhantomJS browser start:
```bash
phantomjs --ssl-protocol=any --ignore-ssl-errors=true vendor/behat/mink-phantomjs-driver/src/Portergeist/Client/main.js 8510 1024 768 2>&1 >> /tmp/gastonjs.log &
```

FAQ
---------

1. Is this a selenium based driver?:

  **NO**, it has nothing to do with Selenium it's inspired on [Poltergeist](https://github.com/teampoltergeist/poltergeist)

2. What features does this driver implements?
  
  **ALL** of them defined in Mink DriverInterface but maximizeWindow, since is a headless browser it does not make sense to implement it

3. Do i need to modify my selenium based tests?

  If you only use the standard behat driver defined methods then NO, you just have to change the default javascript driver.
  

Copyright
---------

Copyright (c) 2015 Juan Francisco Calderon Zumba <juanfcz@gmail.com>
