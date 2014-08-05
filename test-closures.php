<?php
// test-closures.php

$division = function($a, $b)
{
    return $a/$b;
};

function doMath(Closure $function, $a, $b)
{
    $result = $function($a, $b);
    return function() use ($result) {echo $result;};
}

$callback = doMath($division, 9, 3);
$callback().PHP_EOL; // 3

$callback = doMath(function($a, $b) { return $a * $b; }, 9, 2); // 18
$callback().PHP_EOL;
