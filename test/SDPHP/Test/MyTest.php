<?php
/*
 * This file is part of the SDPHP Study Group {studygroup} Package.
 * For the full copyright and license information, 
 * please view the LICENSE file that was distributed 
 * with this source code.
 */

namespace SDPHP\Test;

/**
 * HelloControllerTest - Description. 
 *
 * @author Juan Manuel Torres <juan@cpcstrategy.com>
 * @copyright (c) 2014, SDPHP Study Group Development Team
 */
class MyTest extends \PHPUnit_Framework_TestCase
{
    public function testAssertTrue()
    {
        $this->assertTrue(true);
        $this->assertFalse(false);
        $foo = 'la';
        $bar = $foo;
        $this->assertEquals($foo, $bar, 'Values are not equal.');
    }
}
