<?php
/*
 * This file is part of the SDPHP Study Group {studygroup} Package.
 * For the full copyright and license information, 
 * please view the LICENSE file that was distributed 
 * with this source code.
 */

namespace SDPHP\StudyGroup07;

/**
 * Car - Description. 
 *
 * @author Juan Manuel Torres <juan@cpcstrategy.com>
 * @copyright (c) 2014, SDPHP Study Group Development Team
 */
class Car implements Vehicle {

    const SIZE = 4;

    private $make;
    private $size;
    private $personCount = 0;
    private $personList = ['driver' => [], 'passenger' => []];

    public function __construct($make, $size = 5)
{
    $this->make = $make;
    $this->size = $size;
}

    public function drive()
    {
        return 'Move on ground';
    }

    /**
     * @param Person $person
     * @return string
     * @throws \Exception
     */
    public function addPerson(Person $person)
    {
        $type = $person->getType();

        if ($this->personCount == self::SIZE) {
            throw new \Exception('Car is full, cannot add another person. Remove a person before trying again.');
        }

        if ($type == Person::DRIVER) {
            if (empty($this->personList[Person::DRIVER])) {
                $this->personList[Person::DRIVER][$person->getName()] = $person;
                $this->personCount++;
                return 'Person ' .  $person->getName() . ' is now sitting in the driver seat!';
            } else {
                throw new \Exception('Some one is already driving the car. Remove a driver before trying again.');
            }
        } else {
            $this->personList[Person::PASSENGER][$person->getName()] = $person;
            $this->personCount++;
            return 'Person ' .  $person->getName() . ' is now a passenger in the car!';
        }
    }

    /**
     * @param string $type
     * @return string
     */
    public function removePerson($type = 'passenger')
    {
        if (!empty($this->personList[$type])) {
            $person = array_pop($this->personList[$type]);
            $this->personCount--;
            return 'Person ' . $person->getName() . ' was kicked out of the car.';
        } else {
            return 'There are no people in the car.';
        }
    }

    /**
     * @return int
     */
    public function getPersonCount()
    {
        return $this->personCount;
    }

    /**
     * @return mixed
     */
    public function getMake()
    {
        return $this->make;
    }

    /**
     * @return int
     */
    public function getSize()
    {
        return $this->size;
    }

    public function getPerson($name, $type = Person::PASSENGER)
    {
        return $this->personList[$type][$name];
    }
}
