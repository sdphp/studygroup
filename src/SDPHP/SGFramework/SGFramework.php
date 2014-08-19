<?php
/*
 * This file is part of the SDPHP StudyGroup Package.
 * For the full copyright and license information, 
 * please view the LICENSE file that was distributed 
 * with this source code.
 */
namespace SDPHP\SGFramework;

use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\EventDispatcher\GenericEvent;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\HttpKernel\Controller\ControllerResolver;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;

/**
 * SGFramework - Description. 
 *
 * @author Juan Manuel Torres <kinojman@gmail.com>
 */
class SGFramework implements HttpKernelInterface
{
    /**
     * @var \Symfony\Component\Routing\Matcher\UrlMatcher $matcher
     */
    protected $matcher;

    /**
     * @var \Symfony\Component\HttpKernel\Controller\ControllerResolver $resolver
     */
    protected $resolver;

    /**
     * @var \Symfony\Component\EventDispatcher\EventDispatcher $dispatcher
     */
    protected $dispatcher;


    public function __construct(EventDispatcher $dispatcher, UrlMatcher $matcher, ControllerResolver $resolver)
    {
        $this->dispatcher = $dispatcher;
        $this->matcher    = $matcher;
        $this->resolver   = $resolver;
    }

    /**
     * Handles a Request to convert it to a Response.
     *
     * When $catch is true, the implementation must catch all exceptions
     * and do its best to convert them to a Response instance.
     *
     * @param Request $request A Request instance
     * @param int     $type    The type of the request
     *                          (one of HttpKernelInterface::MASTER_REQUEST or HttpKernelInterface::SUB_REQUEST)
     * @param bool    $catch Whether to catch exceptions or not
     *
     * @return Response A Response instance
     *
     * @throws \Exception When an Exception occurs during processing
     *
     * @api
     */
    public function handle(Request $request, $type = self::MASTER_REQUEST, $catch = true)
    {
        $event = new GenericEvent();
        $event->setArgument('request', $request);
        try {

            $this->dispatcher->dispatch('framework.request', $event);
            // Here we match a path with a route. Match will return an array holding all the
            // information about the route, in this case the result be an array containing a
            // _controller, _route, and lang keys.
            $parameters = $this->matcher->match($request->getPathInfo());

            // Here we have resolved a problem found in the previous front controller
            $request->attributes->add($parameters);

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

            $controller = $this->resolver->getController($request);

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
             * @todo HOW CAN WE RESOLVE MORE THAN ONE ARGUMENT HERE?
             ***************************************/

            $event->setArgument('controller', $controller[0]);
            $event->setArgument('controller_method', $controller[1]);
            $this->dispatcher->dispatch('framework.before_controller', $event);

            /***************************************
             * STEP 4 RESOLVE ARGUMENTS
             * Using the resolver we will get an
             * array of arguments that should be
             * passed to the controller.
             ***************************************/

            $arguments  = $this->resolver->getArguments($request, $controller);

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

        $this->dispatcher->dispatch('framework.after_controller', $event);

        $event->setArgument('response', $response);
        $this->dispatcher->dispatch('framework.response', $event);

        return $response;
    }
}
