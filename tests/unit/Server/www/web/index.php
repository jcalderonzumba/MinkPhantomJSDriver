<?php
require_once __DIR__ . '/../../../../../vendor/autoload.php';

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\File\UploadedFile;

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

$app->get("/basic-auth-required/", function (Request $request) {
  $response = new Response();
  if (!isset($_SERVER["PHP_AUTH_USER"]) || !isset($_SERVER["PHP_AUTH_PW"])) {
    $response->headers->set("WWW-Authenticate", 'Basic realm="TEST_REALM"');
    $response->setStatusCode(401);
    $response->setContent("NOT_AUTHORIZED");
    return $response;
  }

  if ($_SERVER["PHP_AUTH_USER"] != "test" || $_SERVER["PHP_AUTH_PW"] != "test") {
    $response->setStatusCode(401);
    $response->setContent("NOT_AUTHORIZED");
    return $response;
  }
  $htmlContents = file_get_contents(sprintf("%s/static/auth_ok.html", __DIR__));
  $response->setContent($htmlContents);
  $response->setStatusCode(200);
  return $response;
});

//Route used for header related test
$app->get("/check-request-headers/", function (Request $request) {
  $response = new Response();
  $response->headers->set("Content-Type", "application/json");
  $response->setStatusCode(200);
  $jsonResponse = json_encode($request->headers->all());
  $response->setContent($jsonResponse);
  return $response;
});

$app->post("/check-post-request/", function (Request $request) {
  $response = new Response();
  $response->headers->set("Content-Type", "application/json");
  $response->setStatusCode(200);
  $jsonResponse["post"] = $request->request->all();
  $jsonResponse["get"] = $request->query->all();
  if (count($request->files->all()) !== 0) {
    /** @var $file \Symfony\Component\HttpFoundation\File\UploadedFile */
    foreach ($request->files->all() as $file) {
      if ($file instanceof UploadedFile) {
        $jsonResponse["files"][$file->getClientOriginalName()] = array("file_name" => $file->getClientOriginalName(), "is_valid" => $file->isValid(), "mime_type" => $file->getMimeType());
      }
    }
  }
  $jsonResponse = json_encode($jsonResponse);
  $response->setContent($jsonResponse);
  return $response;
});
$app->run();
