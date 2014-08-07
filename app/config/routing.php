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
            '_controller' => array(         // CONTROLLER OPTIONS
                // @todo WHY IS THIS BAD?!
                                            // CREATE A NEW CONTROLLER
                new \SDPHP\StudyGroup02\Controller\HelloWorldController(),
                'translateAction'           // OBJECT METHOD THAT SHOULD BE CALLED
            )
        )
    ));

$routes->add(
    'hello_improved',                       // NAME THE ROUTE
    new Route(                              // CREATE A NEW ROUTE OBJECT
        '/hello/improved/{lang}',           // CHOOSE A PATH AND WILDCARD
        array(
            'lang' => false,                // WILDCARD DEFAULT VALUE
                                            // SPECIFY CONTROLLER AND METHOD, DOES NOT INSTANTIATE OBJECT UNTIL NEEDED
            '_controller' => 'SDPHP\StudyGroup02\Controller\HelloWorldController::translateAction',

        )
    ));
