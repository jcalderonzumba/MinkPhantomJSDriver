<?php
require_once __DIR__ . '/../../../../vendor/autoload.php';

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Cookie;

$filename = __DIR__ . preg_replace('#(\?.*)$#', '', $_SERVER['REQUEST_URI']);
if (php_sapi_name() === 'cli-server' && is_file($filename)) {
  return false;
}

$app = new Application();

$app->get("/testCookiesAreNotEmpty/", function (Request $request) {
  $testResponse = new Response();
  $htmlContents = file_get_contents(sprintf("%s/static/basic.html", __DIR__));
  $testResponse->setContent($htmlContents);
  $testResponse->setStatusCode(200);
  $testResponse->headers->setCookie(new Cookie("a_cookie", "a_has_value"));
  $testResponse->headers->setCookie(new Cookie("b_cookie", "b_has_value"));
  return $testResponse;
});

$app->run();
