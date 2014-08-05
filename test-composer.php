<?php
// test-composer.php

require_once __DIR__.'/vendor/autoload.php';
$client = new GuzzleHttp\Client();

$response = $client->get('https://google.com');
echo $response->getBody();
