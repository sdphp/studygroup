<?php
/*
 * This file is part of the Onema {studygroup} Package. 
 * For the full copyright and license information, 
 * please view the LICENSE file that was distributed 
 * with this source code.
 */
namespace SDPHP\StudyGroup03\Event;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\EventDispatcher\GenericEvent;

/**
 * BeforeAfterSubscriber - Description. 
 *
 * @author Juan Manuel Torres <kinojman@gmail.com>
 * @copyright (c) 2014, onema.io
 */
class BeforeAfterSubscriber implements EventSubscriberInterface
{

    /**
     * Returns an array of event names this subscriber wants to listen to.
     *
     * The array keys are event names and the value can be:
     *
     *  * The method name to call (priority defaults to 0)
     *  * An array composed of the method name to call and the priority
     *  * An array of arrays composed of the method names to call and respective
     *    priorities, or 0 if unset
     *
     * For instance:
     *
     *  * array('eventName' => 'methodName')
     *  * array('eventName' => array('methodName', $priority))
     *  * array('eventName' => array(array('methodName1', $priority), array('methodName2'))
     *
     * @return array The event names to listen to
     *
     * @api
     */
    public static function getSubscribedEvents()
    {
        return array(
            'framework.before_controller'   => array(
                array('onFrameworkBeforeController', 0),
            ),
            'framework.after_controller'    => array(
                array('onFrameworkAfterController', 0)
            ),
        );
    }

    public function onFrameworkBeforeController(GenericEvent $event)
    {
        $controller = $event['controller'];

        if (method_exists($controller, 'before')) {
            $controller->before();
        }
    }

    public function onFrameworkAfterController(GenericEvent $event)
    {
        $controller = $event['controller'];

        if (method_exists($controller, 'after')) {
            $controller->after();
        }
    }
}
