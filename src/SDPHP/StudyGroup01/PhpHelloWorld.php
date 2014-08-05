<?php
/*
 * This file is part of the Onema {test} Package. 
 * For the full copyright and license information, 
 * please view the LICENSE file that was distributed 
 * with this source code.
 */
namespace SDPHP\StudyGroup01\Controller;

/**
 * PhpHelloWorld - Description. 
 *
 * @author Juan Manuel Torres <kinojman@gmail.com>
 * @copyright (c) 2014, onema.io
 */
class PhpHelloWorld 
{
    public function action()
    {
        $this->setSession();
        $hello = $_GET['hello'];
        $world  = $_GET['world'];

        header('Content-Type: text/html; charset=utf-8');
        printf('%s, %s!', $hello, $world);

        // use htmlspecialchars to prevent XSS Attacks
        //sprintf('%s, %s!', htmlspecialchars($hello, ENT_QUOTES, 'UTF-8'), htmlspecialchars($world, ENT_QUOTES, 'UTF-8'));
        return false;
    }

    private function setSession()
    {
        setcookie('session', '1234abcd');
    }
}