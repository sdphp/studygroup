<?php
/*
 * This file is part of the SDPHP Study Group {studygroup} Package.
 * For the full copyright and license information, 
 * please view the LICENSE file that was distributed 
 * with this source code.
 */
namespace SDPHP\Test;

use SDPHP\StudyGroup06\Boat;
use SDPHP\StudyGroup06\Car;
use SDPHP\StudyGroup06\Person;

/**
 * VehicleTest - Description. 
 *
 * @author Juan Manuel Torres <juan@cpcstrategy.com>
 * @copyright (c) 2014, SDPHP Study Group Development Team
 */
class VehiclesTest extends \PHPUnit_Framework_TestCase
{
    public function testCarDrive()
    {
        $car = new Car();
        $expectedResult = 'Move on ground';
        $this->assertEquals($expectedResult, $car->drive());

        return $car;
    }

    public function testBoatDrive()
    {
        $boat = new Boat();
        $expectedResult = 'Move on water';
        $this->assertEquals($expectedResult, $boat->drive());
    }

    public function testPerson()
    {
        $person = new Person('test', 'lala');
        $this->assertEquals('test', $person->getName());
        $this->assertEquals('lala', $person->getType());

        $person->setType(Person::DRIVER);
        $this->assertEquals(Person::DRIVER, $person->getType());
    }

    /**
     * @depends testCarDrive
     * @param $car
     */
    public function testCarAddDriver($car)
    {
        if ($car instanceof Car) {
            $person = new Person('Juan', Person::DRIVER);

            $message = $car->addperson($person);
            $this->assertEquals(1, $car->getPersonCount(), 'Car count is incorrect. There should not be more or less than 1 person in the car.');
            $this->assertEquals('Person ' .  $person->getName() . ' is now sitting in the driver seat!', $message);
        }

        return $car;
    }

    /**
     * @depends testCarAddDriver
     * @param $car
     */
    public function testCarAddPassenger($car)
    {
        if ($car instanceof Car) {
            $person = new Person('Jolene', Person::PASSENGER);

            $message = $car->addPerson($person);
            $this->assertEquals(2, $car->getPersonCount(), 'Car count is incorrect. There should not be more or less than 2 persons in the car.');
            $this->assertEquals('Person ' .  $person->getName() . ' is now a passenger in the car!', $message);
        }

        return $car;
    }

    /**
     * @depends testCarAddPassenger
     * @param $car
     */
    public function testCarDriverException($car)
    {
        $this->setExpectedException('\Exception', 'Some one is already driving the car. Remove a driver before trying again.');

        if ($car instanceof Car) {
            $person = new Person('John', Person::DRIVER);
            $car->addPerson($person);
        }
    }

    /**
     * @depends testCarAddPassenger
     * @param $car
     */
    public function testCarIsFullException($car)
    {
        $this->setExpectedException('\Exception', 'Car is full, cannot add another person. Remove a person before trying again.');

        if ($car instanceof Car) {
            $passenger2     = new Person('Rick',    Person::PASSENGER);
            $passenger3     = new Person('Anthony', Person::PASSENGER);
            $extraPassenger = new Person('Lewis',   Person::PASSENGER);

            $car->addPerson($passenger2);
            $car->addPerson($passenger3);
            $car->addPerson($extraPassenger);
        }

        return $car;
    }

    /**
     * @depends testCarAddPassenger
     * @param $car
     */
    public function testRemovePersonInOrder($car)
    {
        if ($car instanceof Car) {
            $this->assertEquals(4, $car->getPersonCount(), 'Car count is incorrect. There should not be more or less than 4 persons in the car.');

            $message = $car->removePerson(Person::PASSENGER);
            $this->assertEquals(3, $car->getPersonCount(), 'Car count is incorrect. There should not be more or less than 3 persons in the car.');
            $this->assertEquals('Person Anthony was kicked out of the car.', $message, 'Message returned by the removePerson method is not correct.');

            $message = $car->removePerson(Person::PASSENGER);
            $this->assertEquals(2, $car->getPersonCount(), 'Car count is incorrect. There should not be more or less than 2 persons in the car.');
            $this->assertEquals('Person Rick was kicked out of the car.', $message, 'Message returned by the removePerson method is not correct.');

            $message = $car->removePerson(Person::PASSENGER);
            $this->assertEquals(1, $car->getPersonCount(), 'Car count is incorrect. There should not be more or less than 1 persons in the car.');
            $this->assertEquals('Person Jolene was kicked out of the car.', $message, 'Message returned by the removePerson method is not correct.');

            $message = $car->removePerson(Person::DRIVER);
            $this->assertEquals(0, $car->getPersonCount(), 'Car count is incorrect. There should not be more or less than 0 persons in the car.');
            $this->assertEquals('Person Juan was kicked out of the car.', $message, 'Message returned by the removePerson method is not correct.');

            $message = $car->removePerson(Person::PASSENGER);
            $this->assertEquals(0, $car->getPersonCount(), 'Car count is incorrect. There should not be more or less than 0 persons in the car.');
            $this->assertEquals('There are no people in the car.', $message, 'Message returned by the removePerson method is not correct.');

            $message = $car->removePerson(Person::DRIVER);
            $this->assertEquals(0, $car->getPersonCount(), 'Car count is incorrect. There should not be more or less than 0 persons in the car.');
            $this->assertEquals('There are no people in the car.', $message, 'Message returned by the removePerson method is not correct.');
        }
    }
}
