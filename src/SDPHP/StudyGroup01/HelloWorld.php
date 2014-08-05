<?php
/*
 * This file is part of the Onema {test} Package. 
 * For the full copyright and license information, 
 * please view the LICENSE file that was distributed 
 * with this source code.
 *
 * src/SDPHP/StudyGroup01/HelloWorld.php
 */
namespace SDPHP\StudyGroup01\Controller;

use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * HelloWorld.php - Description. 
 *
 * @author Juan Manuel Torres <kinojman@gmail.com>
 * @copyright (c) 2014, onema.io
 */
class HelloWorld
{
    public function action(Request $request)
    {
        $hello = $request->query->get('hello');
        $world = $request->query->get('world');
        $greeting = sprintf('%s, %s!', htmlspecialchars($hello, ENT_QUOTES, 'UTF-8'), htmlspecialchars($world, ENT_QUOTES, 'UTF-8'));

        $response = new Response(
            $greeting,
            Response::HTTP_OK,
            ['content-type' => 'text/html']
        );

        $this->setSession($response);

        return $response;
    }

    private function setSession(Response $response)
    {
        $response->headers->setCookie(new Cookie('session', '1234abcde'));
    }
}