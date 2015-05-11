<?php
/**
 * Created by PhpStorm.
 * User: juan
 * Date: 11/05/15
 * Time: 17:48
 */

require_once "../vendor/autoload.php";

$pjsDriver = new \Behat\Mink\Driver\PhantomJSDriver();

$pjsDriver->start();
$pjsDriver->visit("http://www.google.es");
echo $pjsDriver->getContent();
