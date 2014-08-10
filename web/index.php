<?php
/**
 * web/index.php
 *
 * This is a basic example aimed to understand the following concepts:
 *
 * 1) Symfony Routing
 * 2) Basic HTTPKernel workflow
 * 3) Resolving Controller
 * 4) Resolving Arguments
 * 5) Advanced use of controllers
 *
 * NOTE 1: this front controller step 1-6 loosely follows the Symfony HTTPKernel Component process described here:
 * {@link http://symfony.com/doc/current/components/http_kernel/introduction.html HTTPKernel Component}
 * NOTE 2: this code SHOULD NOT be used in ANY application.
 *
 * Also see:
 * @link http://fabien.potencier.org/article/55/create-your-own-framework-on-top-of-the-symfony2-components-part-6
 */

require_once "../vendor/autoload.php";

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;


$request = Request::createFromGlobals();

/***************************************
 * STEP 1 ROUTING
 * figure out information about the
 * request. We are now using the router
 * component to get more
 * information about the request.
 *
 * We are also decoupling the routes
 * from our front controller, making
 * routes more flexible and powerful.
 ***************************************/

// Get the routing information, will initialize the $routes object.
include __DIR__ . '/../app/config/routing.php';

// Context holds information about a request
$context = new RequestContext();
$context->fromRequest($request);

// Matches a URL path with a set of routes
$matcher = new UrlMatcher($routes, $context);

// Here we match a path with a route. Match will return an array holding all the
// information about the route, in this case the result be an array containing a
// _controller, _route, and lang keys.
$parameters = $matcher->match($request->getPathInfo());

// Extract is used to import into the symbol table variables contained in an associative array.
// This would automatically create variables using the names of the associative array. For the
// sake of clarity we abstain of using this method.
//extract($parameters, EXTR_SKIP);

// This is a bit more clear as match returns an array containing lang, _controller and _route.
list($lang, $_controller, $_route) = array_values($parameters);

// Add a specific attribute to the request with the language
// @todo WHAT IS WRONG WITH THIS PARTICULAR STEP?
$request->attributes->add(array('lang' => $lang));

try {
    /***************************************
     * STEP 2 RESOLVE THE CONTROLLER
     * We use the matcher from STEP 1
     * to determine and prepare the
     * controller we will be using to
     * handle the request and return a
     * response.
     ***************************************/
    $controller = $_controller[0];
    $method     = $_controller[1];


    /***************************************
     * STEP 3 INITIALIZE CONTROLLER
     * In this step we can change the
     * controller right before is executed.
     * We have no need to modify the
     * controller for this version of the
     * application.
     *
     * If required this step could change
     * the controller completely.
     ***************************************/


    /***************************************
     * STEP 4 RESOLVE ARGUMENTS
     * We are not resolving anything
     * here, because our simple application
     * only accepts one parameter: a request
     * object.
     *
     * @todo HOW CAN WE RESOLVE MORE THAN ONE ARGUMENT HERE?
     ***************************************/


    /***************************************
     * STEP 5 CALL CONTROLLER
     * we call the controller using the
     * object created in step 2. The controller
     * should create a response for the
     * given request. The process of creating
     * the response is up to the developer
     * of the application: YOU!
     *
     * NOTE: the controller could call a
     * view (templating engine) to generate
     * a response in the proper format.
     ***************************************/
    $response = $controller->$method($request);

} catch (ResourceNotFoundException $e) {

    /**
     * @todo WHAT IS WRONG WITH THESE EXCEPTIONS? HINT: THINK ABOUT SECURITY!
     */
    $response = new Response('Not Found: ' . $e->getMessage(), 404);

} catch (\Exception $e) {

    $response = new Response('An error occurred: ' . $e->getMessage(), 500);

}

/***************************************
 * STEP 6 SEND A RESPONSE BACK
 * Use the prepare method to ensure that
 * the response complies with the
 * request and send the response back
 * to the client.
 ***************************************/

$response->prepare($request);
$response->send();
