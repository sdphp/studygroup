<?php
//test-traits.php

trait Hello {
    function sayHello() {
        return "Hello";
    }
}
trait World {
    function sayWorld() {
        return "World!";
    }
}
class MyWorld {
    use Hello, World;
}

$world = new MyWorld();
echo $world->sayHello() . ' ' . $world->sayWorld(); //Hello World