<?php
/*
 * This file is part of the SDPHP Study Group {studygroup} Package.
 * For the full copyright and license information, 
 * please view the LICENSE file that was distributed 
 * with this source code.
 */
namespace SDPHP\StudyGroup07;

/**
 * Person - Description. 
 *
 * @author Juan Manuel Torres <juan@cpcstrategy.com>
 * @copyright (c) 2014, SDPHP Study Group Development Team
 */
class Person 
{
    const PASSENGER = 'passenger';
    const DRIVER = 'driver';

    /**
     * @var string
     */
    private $type;
    /**
     * @var string
     */
    private $name;

    /**
     * @param $name
     * @param string $type
     */
    public function __construct($name, $type = 'passenger')
    {
        $this->type = $type;
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param string $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }
}
