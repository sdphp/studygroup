<?php
/*
 * This file is part of the Onema {test} Package. 
 * For the full copyright and license information, 
 * please view the LICENSE file that was distributed 
 * with this source code.
 *
 * src/SDPHP/StudyGroup01/HelloWorld.php
 */
namespace SDPHP\StudyGroup05\Controller;

use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;

/**
 * HelloWorld.php - Class use to translate "hello world" to different languages.
 *
 * @author Juan Manuel Torres <kinojman@gmail.com>
 * @copyright (c) 2014, onema.io
 */
class HelloWorldController
{
    /**
     * list of available translations for "hello world"
     * @todo HOW CAN WE DECOUPLE OUR TRANSLATIONS FROM THIS CLASS?
     */
    public static $greetings = array(
        'ES' => 'Hola Mundo!',                  //Spanish
        'EN' => 'Hello World!',                 //English
        'FR' => 'Bonjour tout le monde!',       //French
        'DE' => 'Hallo Welt',                   //German
        'DA' => 'Hej verden',                   //Danish
        'SW' => 'Hujambo dunia',                //Swahili
        'IT' => 'Ciao mondo'                    //Italian
    );

    /**
     * @todo must implement
     */
    public function before()
    {
        // ...
    }

    public function translateAction(Request $request)
    {
        // In the front controller we added the lang parameter as a request attribute.
        $rawLanguage = $request->attributes->get('lang');
        $request->getSession();
        if (empty($rawLanguage)) {
            // if no language is available check if we have one stored in a session,
            // if none a default one will be returned.
            $language = $this->getLanguageFromSession($request->getSession());
        } else {
            // clean user input
            $language = htmlspecialchars($rawLanguage, ENT_QUOTES, 'UTF-8');
        }

        $greeting = $this->getGreeting($language);

        if ($greeting) {
            $response = new Response(
                $greeting,
                Response::HTTP_OK,
                array('content-type' => 'text/html')
            );
        } else {
            // Here we could generate a response and return it OR
            // throw the proper exception which will be handled by our framework.
            // @todo CAN YOU FIND A POTENTIAL PROBLEM BY USING A RESPONSE AN NOT AN EXCEPTION?
            // $response = new Response('Language ' . $language . ' is not supported', Response::HTTP_NOT_FOUND);
            throw new ResourceNotFoundException('Language ' . $language . ' is not supported');
        }

        // Set the language in the session
        $this->setSession($response, $language);

        $response->setTtl(20);

        return $response;
    }

    /**
     * @todo must implement
     */
    public function after()
    {
        // ...
    }

    /**
     * Set the language value in a cookie.
     * This is use to remember the language preference of the user.
     *
     * @param Response $response
     * @param $language
     */
    private function setSession(Response $response, $language)
    {
        $response->headers->setCookie(new Cookie('language', $language));
    }

    /**
     * Get the value of the language stored in a cookie. If none is set
     * "ES" will be returned as default value.
     *
     * @param SessionInterface $session
     * @return string Returns ES as default value.
     */
    private function getLanguageFromSession(SessionInterface $session)
    {
        // if no session is found we will use ES as default
        return $session->get('language', 'ES');
    }

    /**
     * Using a ternary operator to figure out if the language is exist in the greetings array.
     * Ternary operator work as follow:
     * <code>
     *     <condition> ? <true-case-code> : <false-case-code>;
     * </code>
     *
     * and it is equivalent to:
     *
     * <code>
     *    if(<condition>) {
     *        <true-case-code>
     *    } else {
     *        <false-case-code>
     *    }
     * </code>
     *
     *
     * @param $language
     * @return string | bool
     * @todo HOW CAN WE IMPROVE THIS CODE? HINT: getGreeting COULD DO A BETTER JOB ABOUT DEALING WITH THE "NOT FOUND" CASE
     */
    private function getGreeting($language)
    {
        return isset(self::$greetings[$language]) ? self::$greetings[$language] : false;
    }
}
