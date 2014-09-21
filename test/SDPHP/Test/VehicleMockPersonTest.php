<?php
/*
 * This file is part of the SDPHP Study Group {studygroup} Package.
 * For the full copyright and license information, 
 * please view the LICENSE file that was distributed 
 * with this source code.
 */
namespace SDPHP\Test;

use SDPHP\StudyGroup06\Car;
use SDPHP\StudyGroup06\Person;

/**
 * VehicleTest - Description. 
 *
 * @author Juan Manuel Torres <juan@cpcstrategy.com>
 * @copyright (c) 2014, SDPHP Study Group Development Team
 */
class VehiclesMockPersonTest extends \PHPUnit_Framework_TestCase
{
    protected $passengerMock;
    protected $driverMock;

    public function setup()
    {
        // PASSENGER
        $this->passengerMock =
        $this->getMockBuilder('\SDPHP\StudyGroup06\Person')
             ->disableOriginalConstructor()
             ->setMethods(['getType', 'getName'])
             ->getMock();

        $this->passengerMock
            ->expects($this->any())
            ->method('getType')
            ->willReturn('passenger');

        $this->passengerMock
            ->expects($this->any())
            ->method('getName')
            ->willReturn('robot-passenger');


        // DRIVER
        $this->driverMock =
        $this->getMockBuilder('\SDPHP\StudyGroup06\Person')
             ->disableOriginalConstructor()
             ->setMethods(['getType', 'getName'])
             ->getMock();

        $this->driverMock
             ->expects($this->any())
             ->method('getType')
             ->willReturn(Person::DRIVER);

        $this->driverMock
             ->expects($this->any())
             ->method('getName')
             ->willReturn('robot-driver');
    }

    public function testCarAddDriver()
    {
        $car = new Car();
        $message = $car->addperson(clone $this->driverMock);
        $this->assertEquals(1, $car->getPersonCount(), 'Car count is incorrect. There should not be more or less than 1 person in the car.');
        $this->assertEquals('Person robot-driver is now sitting in the driver seat!', $message);

        return $car;
    }

    /**
     * @depends testCarAddDriver
     * @param $car
     */
    public function testCarAddPassenger($car)
    {
        if ($car instanceof Car) {
            $message = $car->addPerson(clone $this->passengerMock);
            $this->assertEquals(2, $car->getPersonCount(), 'Car count is incorrect. There should not be more or less than 2 persons in the car.');
            $this->assertEquals('Person ' .  $this->passengerMock->getName() . ' is now a passenger in the car!', $message);
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
            $driverClone = clone $this->driverMock;

            $car->addPerson($driverClone);
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
            $passengerClone1 = clone $this->passengerMock;
            $passengerClone2 = clone $this->passengerMock;
            $passengerClone3 = clone $this->passengerMock;
            $car->addPerson($passengerClone1);
            $car->addPerson($passengerClone2);
            $car->addPerson($passengerClone3);
        }

        return $car;
    }

    /**
     * @depends testCarAddPassenger
     * @param $car
     */
    public function testRemovePerson($car)
    {
        if ($car instanceof Car) {
            $this->assertEquals(4, $car->getPersonCount(), 'Car count is incorrect. There should not be more or less than 4 persons in the car.');

            $message = $car->removePerson(Person::PASSENGER);
            $this->assertEquals(3, $car->getPersonCount(), 'Car count is incorrect. There should not be more or less than 3 persons in the car.');
            $this->assertEquals('Person robot-passenger was kicked out of the car.', $message, 'Message returned by the removePerson method is not correct.');

            $message = $car->removePerson(Person::PASSENGER);
            $this->assertEquals(2, $car->getPersonCount(), 'Car count is incorrect. There should not be more or less than 2 persons in the car.');
            $this->assertEquals('Person robot-passenger was kicked out of the car.', $message, 'Message returned by the removePerson method is not correct.');

            $message = $car->removePerson(Person::PASSENGER);
            $this->assertEquals(1, $car->getPersonCount(), 'Car count is incorrect. There should not be more or less than 1 persons in the car.');
            $this->assertEquals('Person robot-passenger was kicked out of the car.', $message, 'Message returned by the removePerson method is not correct.');

            $message = $car->removePerson(Person::DRIVER);
            $this->assertEquals(0, $car->getPersonCount(), 'Car count is incorrect. There should not be more or less than 0 persons in the car.');
            $this->assertEquals('Person robot-driver was kicked out of the car.', $message, 'Message returned by the removePerson method is not correct.');

            $message = $car->removePerson(Person::PASSENGER);
            $this->assertEquals(0, $car->getPersonCount(), 'Car count is incorrect. There should not be more or less than 0 persons in the car.');
            $this->assertEquals('There are no people in the car.', $message, 'Message returned by the removePerson method is not correct.');

            $message = $car->removePerson(Person::DRIVER);
            $this->assertEquals(0, $car->getPersonCount(), 'Car count is incorrect. There should not be more or less than 0 persons in the car.');
            $this->assertEquals('There are no people in the car.', $message, 'Message returned by the removePerson method is not correct.');
        }
    }
}
