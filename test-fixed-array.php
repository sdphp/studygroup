<?php
// test-fixed-array.php

class Test {
    public function __destruct(){
        echo "Destroying class ".__CLASS__;
    }
}

$array = new \SplFixedArray(6);
$array[5] = new Test();
$array->setSize(5); // Destroying class Test