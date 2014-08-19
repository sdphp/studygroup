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
use Symfony\Component\HttpFoundation\Response;

/**
 * TestEvent.
 *
 * @author Juan Manuel Torres <kinojman@gmail.com>
 * @copyright (c) 2014, onema.io
 */
class TestSubscriber implements EventSubscriberInterface
{

    public static function getSubscribedEvents()
    {
        return array(
            'framework.request' => array('onFrameworkRequest', 10),
            'framework.response'            => array('onFrameworkResponse', 0),

            'framework.before_controller'   => array(
                array('onFrameworkBeforeController', 10),
            ),
            'framework.after_controller'    => array('onFrameworkAfterController', 0),
        );
    }

    /**
     * This method will overwrite the default language by setting the language cookie.
     *
     * @param GenericEvent $event
     */
    public function onFrameworkRequest(GenericEvent $event)
    {
        // Change Default language
        $request = $event->getArgument('request');
        $language = $request->cookies->get('language');

        if (!isset($language)) {
            $request->cookies->set('language', 'DE');
        }
    }

    /**
     * @param GenericEvent $event
     */
    public function onFrameworkBeforeController(GenericEvent $event)
    {
        // ...
    }

    /**
     * @param GenericEvent $event
     */
    public function onFrameworkAfterController(GenericEvent $event)
    {
        // ...
    }

    /**
     * @param GenericEvent $event
     */
    public function onFrameworkResponse(GenericEvent $event)
    {
        $response = $event->getArgument('response');

        if ($response instanceof Response) {
            $content = $response->getContent();
            $response->setContent('<p style="background:#ccc; color:#fff; font-size: 22px; font-family: Slab,Georgia,serif; border: solid black 1px;">'.$content.'</p>');
        }
    }
}
