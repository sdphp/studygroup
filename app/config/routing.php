<?php

use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

$routes = new RouteCollection();

$routes->add(
    'hello',                                // NAME THE ROUTE
    new Route(                              // CREATE A NEW ROUTE OBJECT
        '/hello/{lang}',                    // CHOOSE A PATH AND WILDCARD
        array(
            'lang' => false,                // WILDCARD DEFAULT VALUE
                                            // SPECIFY CONTROLLER AND METHOD, DOES NOT INSTANTIATE OBJECT UNTIL NEEDED
            '_controller' => 'SDPHP\StudyGroup03\Controller\HelloWorldController::translateAction',

        ),
        array(
            'lang' => '.+',                 // ALLOW LANG TO CONTAIN A TRAILING SLASH
        )
    ));

// THIS ROUTE IS NOT RECOMMENDED BECAUSE IT INSTANTIATES THE CLASS
$routes->add(
    'hello_BAD',                            // NAME THE ROUTE
    new Route(                              // CREATE A NEW ROUTE OBJECT
        '/hello/BAD/{lang}',                // CHOOSE A PATH AND WILDCARD
        array(
            'lang' => false,                // WILDCARD DEFAULT VALUE
            '_controller' => array(         // CONTROLLER OPTIONS
                // @todo WHY IS THIS BAD?!
                // CREATE A NEW CONTROLLER
                new \SDPHP\StudyGroup03\Controller\HelloWorldController(),
                'translateAction'           // OBJECT METHOD THAT SHOULD BE CALLED
            )
        )
    ));

$routes->add(
    'hello_inject_two_parameters',          // NAME THE ROUTE
    new Route(                              // CREATE A NEW ROUTE OBJECT
        '/hello/inject/two/{lang}',         // CHOOSE A PATH AND WILDCARD
        array(
            'lang' => false,                // WILDCARD DEFAULT VALUE
                                            // SPECIFY CONTROLLER AND METHOD, DOES NOT INSTANTIATE OBJECT UNTIL NEEDED
            '_controller' => 'SDPHP\StudyGroup02\Controller\HelloWorldController::twoParametersAction',

        )
    ));

$routes->add(
    'test_error_no_controller',
    new Route(
        '/test/no/controller',
        array(
            '_controller' => 'SDPHP\StudyGroup\NoController::noMethod'
        )
    )
);

$routes->add(
    'test_error_no_method',
    new Route(
        '/test/no/method',
        array(
            '_controller' => 'SDPHP\StudyGroup03\Controller\HelloWorldController::noMethod',
        )
    )
);