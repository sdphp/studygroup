<?php
// web/index.php

require_once "../vendor/autoload.php";

use Symfony\Component\HttpFoundation\Request;

$request = Request::createFromGlobals();

$path = $request->getPathInfo();
$class = '\SDPHP\StudyGroup01\Controller\\' . trim($path, '/');

if (class_exists($class)) {
    $controller = new $class();
    $response = $controller->action($request);

    if ($response) {
        $response->prepare($request);
        $response->send();
    }
}
