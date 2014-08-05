<?php
/**
 * web/index.php
 *
 * This is a basic example aimed to understand the following concepts:
 *
 * 1) what is an HTTP request
 * 2) what is an HTTP response
 * 3) How to handle a request and generate a response using plane PHP
 * 4) How to handle a request and generate a response using Symfony's HTTP Foundation component
 * 5) Understand the basic steps involved during the process of converting a request into a response
 *
 * NOTE 1: this front controller step 1-6 LOOSELY follows the Symfony HTTPKernel Component process described here:
 * {@link http://symfony.com/doc/current/components/http_kernel/introduction.html HTTPKernel Component}
 * NOTE 2: this code should not be used in a real application.
 *
 * Also see:
 * @link http://slides.com/onema/http-protocol#/
 * @link http://symfony.com/doc/current/components/http_foundation/introduction.html
 */

require_once "../vendor/autoload.php";

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


$request = Request::createFromGlobals();

/***************************************
 * STEP 1 ROUTING
 * figure out information about the
 * request. In this case we only use
 * the path.
 ***************************************/

// Get the resource path e.g. /HelloWorld
$path = $request->getPathInfo();

/***************************************
 * STEP 2 RESOLVE THE CONTROLLER
 * We use the information from STEP 1
 * to figure out what class (controller)
 * we will be using to handle the
 * request and return a response.
 * NOTE: Unlike symfony2 this step will not
 * return a callable.
 ***************************************/

// Remove forward slashes and append to controller namespace
// The result will be a fully qualified class name: namespace+class
$class = '\SDPHP\StudyGroup01\Controller\\' . trim($path, '/');


if (class_exists($class)) {

    /***************************************
     * STEP 3 INITIALIZE CONTROLLER
     * In this step we initialize the
     * controller that will handle the
     * request.
     ***************************************/

    // Check if the class exists and if so create an new
    // instance of the class and call the "action" method
    $controller = new $class();

    /***************************************
     * STEP 4 RESOLVE ARGUMENTS
     * We are not resolving anything
     * here, because our simple application
     * only deals with one method per
     * controller, and this method only
     * should accept one parameter.
     ***************************************/

    // NO STEP 4 IS IMPLEMENTED IN THIS APP

    /***************************************
     * STEP 5 CALL CONTROLLER
     * we call the controller using the
     * object created in step 3. The controller
     * should create a response for the
     * given request. The process of creating
     * the response is up to the developer
     * of the application: YOU!
     *
     * NOTE: the controller could call a
     * view (templating engine) to generate
     * a response in the proper format.
     ***************************************/
    $response = $controller->action($request);

} else {
    // The class doesn't exist; we still need to deal with the request.
    // Create a body with a Not Found message and send back with the
    // Appropriate code: 404 NOT FOUND.
    $html = '<html><body><h1>Page Not Found</h1></body></html>';
    $response = new Response($html, Response::HTTP_NOT_FOUND);
}
/***************************************
 * STEP 6 SEND A RESPONSE BACK
 * Use the prepare method to ensure that
 * the response complies with the
 * request and send the response back
 * to the client.
 ***************************************/

// This IF only exist because we deal with responses in two ways
// 1. we use plain PHP and return false to identify that no object was returned
// 2. we use objects (Symfony's HTTP Foundation)
// From now on we will use
if ($response) {
    $response->prepare($request);
    $response->send();
}