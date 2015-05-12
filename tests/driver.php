<?php
/**
 * Created by PhpStorm.
 * User: juan
 * Date: 11/05/15
 * Time: 17:48
 */

require_once "../vendor/autoload.php";

$pjsDriver = new \Behat\PhantomJSExtension\Driver\PhantomJSDriver("/Users/juan/code/scm/pjsdriver/bin/phantomjs", "/Users/juan/code/scm/pjsdriver/bin/phantomloader", "http://wwww.google.es");

$pjsDriver->start();
$pjsDriver->visit("http://ft.devsnt.com");
