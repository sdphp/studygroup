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
use Symfony\Component\Routing;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\Loader\PhpFileLoader;
use Symfony\Component\Routing\Loader\YamlFileLoader;
use Symfony\Component\HttpKernel\Controller\ControllerResolver;
use Symfony\Component\HttpKernel\HttpCache\HttpCache;
use Symfony\Component\HttpKernel\HttpCache\Store;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpFoundation\Session\Session;
use SDPHP\StudyGroup05\Event\TestSubscriber;
use SDPHP\StudyGroup05\Event\BeforeAfterSubscriber;
use SDPHP\SGFramework\SGFramework;

$request = Request::createFromGlobals();
//DELETE THIS! $request->setSession(new Session(new ))

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

// NEW!!! Using the Symfony2 Config Component
// Load routes Using file loaders. This will enable us to
// change the routing configuration fom PHP to YAML or XML!
$locator = new FileLocator(array(__DIR__ . '/../app/config'));
//$loader = new PhpFileLoader($locator);
//$routes = $loader->load('routes.php');

// Use the YAML file loader to load and parse routes from YAML
// This requires the YAML component and is not installed by default
$loader = new YamlFileLoader($locator);
$routes = $loader->load('routes.yml');
$request->setSession(new Session());

// Context holds information about a request
$context = new RequestContext();
$context->fromRequest($request);

$matcher = new UrlMatcher($routes, $context);
$resolver = new ControllerResolver();

// Create a new Event Dispatcher and add a custom subscriber,
// Subscribers tell the dispatcher which events they want to
// listen to
$dispatcher = new EventDispatcher();
$dispatcher->addSubscriber(new TestSubscriber());
$dispatcher->addSubscriber(new BeforeAfterSubscriber());

// Any callable can be added to the dispatcher. Below is an
// example of how to add a specific class method as a listener.
// We must specify the events to listen for.
//$testEvent = new TestEvent();
//$dispatcher->addListener('framework.request',  array($testEvent, 'onFrameworkRequest'));
//$dispatcher->addListener('framework.response', array($testEvent, 'onFrameworkResponse'));

// Initialize framework and give it the request to handle
$framework = new SGFramework($dispatcher, $matcher, $resolver);

// Add HTTP Caching
$framework = new HttpCache($framework, new Store(__DIR__.'/../app/cache'));

$response = $framework->handle($request);

/***************************************
 * STEP 6 SEND A RESPONSE BACK
 * Use the prepare method to ensure that
 * the response complies with the
 * request and send the response back
 * to the client.
 ***************************************/

$response->prepare($request);
$response->send();