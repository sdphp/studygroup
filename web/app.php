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
 * Also see:
 * @link http://fabien.potencier.org/article/55/create-your-own-framework-on-top-of-the-symfony2-components-part-11
 */

require_once "../vendor/autoload.php";

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing;
use Symfony\Component\Routing\Loader\YamlFileLoader as RoutingLoader;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader as ServiceLoader;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\DependencyInjection\ContainerBuilder;

$request = Request::createFromGlobals();
$request->setSession(new Session());
$locator = new FileLocator(realpath(__DIR__ . '/../app/config'));

$container = new ContainerBuilder();
$serviceLoader = new ServiceLoader($container, $locator);
$serviceLoader->load('services.yml');
$container->register('service_container', $container);


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

// Load routes and set them as a parameter in the container
$loader = new RoutingLoader($locator);
$routes = $loader->load('routes.yml');
$container->setParameter('routes', $routes);

// Initialize framework and give it the request to handle
$framework = $container->get('framework');

// Add HTTP Caching
$framework = new HttpCache($framework, new Store(__DIR__.'/../app/cache'));

// Handle request
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