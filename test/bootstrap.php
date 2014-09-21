<?php

if (!isset($_ENV['AUTOLOADER'])) {
    $_ENV['AUTOLOADER'] = '/../vendor/autoload.php';
}

require_once __DIR__ . $_ENV['AUTOLOADER'];
