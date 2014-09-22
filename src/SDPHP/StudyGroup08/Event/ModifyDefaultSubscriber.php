<?php
/*
 * This file is part of the Onema {studygroup} Package. 
 * For the full copyright and license information, 
 * please view the LICENSE file that was distributed 
 * with this source code.
 */
namespace SDPHP\StudyGroup08\Event;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * TestEvent.
 *
 * @author Juan Manuel Torres <kinojman@gmail.com>
 * @copyright (c) 2014, onema.io
 */
class ModifyDefaultSubscriber implements EventSubscriberInterface
{

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::REQUEST    => ['onFrameworkRequest', 10],
            KernelEvents::RESPONSE   => ['onFrameworkResponse', 0],
        ];
    }

    /**
     * This method will overwrite the default language by setting the language cookie.
     *
     * @param \Symfony\Component\HttpKernel\Event\GetResponseEvent $event
     */
    public function onFrameworkRequest(GetResponseEvent $event)
    {
        // Change Default language
        $request = $event->getRequest();
        $language = $request->getSession()->get('language');

        if (!isset($language)) {
            $request->getSession()->set('language', 'DE');
        }
    }

    /**
     * @param \Symfony\Component\HttpKernel\Event\FilterResponseEvent $event
     */
    public function onFrameworkResponse(FilterResponseEvent $event)
    {
        $response = $event->getResponse();

        if ($response instanceof Response) {
            $content = $response->getContent();
            $response->setContent('<p style="background:#ccc; color:#fff; font-size: 22px; font-family: Slab,Georgia,serif; border: solid black 1px;">'.$content.'</p>');
        }
    }
}
