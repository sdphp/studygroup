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
 * NOTE 2: this code could be used in a simple application.
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
use Symfony\Component\HttpKernel\Controller\ControllerResolver;


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

// Get the resource path e.g. /HelloWorld
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

// Here we have resolved a problem found in the previous front controller
// @todo WHY IS THIS BETTER THAN ADDING OUR OWN ATTRIBUTES?
$request->attributes->add($matcher->match($request->getPathInfo()));

try {
    /***************************************
     * STEP 2 RESOLVE THE CONTROLLER
     * We use the matcher from STEP 1
     * to determine and prepare the
     * controller we will be using to
     * handle the request and return a
     * response.
     *
     * Here we use Symfony's ControllerResolver
     * but we could also implement our own
     * resolver by creating a new class that
     * implements the
     * Symfony\Component\HttpKernel\Controller\ControllerResolverInterface
     * For more info see:
     * @link http://symfony.com/doc/current/components/http_kernel/introduction.html#resolve-the-controller
     *
     * The job of the Symfony resolver is to get
     * a request that contains routing information
     * (added to the attributes in STEP 1)
     * and returns a controller (getController)
     * and the method arguments (getArguments, see STEP 4)
     ***************************************/

    $resolver = new ControllerResolver();
    $controller = $resolver->getController($request);

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
     *
     * @todo HOW CAN THE CONTROLLER BE PROPERLY MODIFY HERE?
     ***************************************/


    /***************************************
     * STEP 4 RESOLVE ARGUMENTS
     * Using the resolver we will get an
     * array of arguments that should be
     * passed to the controller.
     ***************************************/

    $arguments  = $resolver->getArguments($request, $controller);

    /***************************************
     * STEP 5 CALL CONTROLLER
     * we call the controller using the
     * object created in step 2. The controller
     * should create a response for the
     * given request.
     *
     * We are not using the class directly
     * as we did before, instead we will
     * start using the PHP function
     * call_user_func_array. It "calls a
     * callback with an array of parameters"
     * that way we can pass any number of
     * parameters to the class method we
     * want to invoke. It is up to the
     * ControllerResolver to figure out
     * what needs to be returned.
     *
     * The process of creating
     * the response is up to the developer
     * of the application: YOU!
     *
     * NOTE: the controller could call a
     * view (templating engine) to generate
     * a response in the proper format.
     ***************************************/
    $response = call_user_func_array($controller, $arguments);

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