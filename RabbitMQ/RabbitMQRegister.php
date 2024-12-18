#!/usr/bin/php
<?php 
require_once('ComponentsofRabbitClient/path.inc');
require_once('ComponentsofRabbitClient/get_host_info.inc');
require_once('ComponentsofRabbitClient/rabbitMQLib.inc');

$client = new rabbitMQClient("testRabbitMQ.ini", "notaweatherapp");

if (isset($argv[1]) && isset($argv[2])) {
    $username = $argv[1];
    $password = $argv[2];
} else {
    echo "Usage: " . $argv[0] . " <username> <password>\n";
    exit(1);
}

$request = array();
$request['type'] = "Registration";
$request['username'] = $username;
$request['password'] = $password;
$request['message'] = "Requesting registration";

$response = $client->send_request($request);

echo "Client received response: " . PHP_EOL;
print_r($response);
echo PHP_EOL;

if ($response['returnCode'] === '0') {
    echo "Registration successful! Welcome, " . $username . PHP_EOL;
} else {
    echo "Registration failed: " . $response['message'] . PHP_EOL;
}

echo $argv[0] . " END" . PHP_EOL;

?>