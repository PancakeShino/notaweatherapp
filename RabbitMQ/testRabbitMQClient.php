#!/usr/bin/php
<?php
<<<<<<< HEAD
require_once('ComponentsofRabbitClient/path.inc');
require_once('ComponentsofRabbitClient/get_host_info.inc');
require_once('ComponentsofRabbitClient/rabbitMQLib.inc');

$client = new rabbitMQClient("testRabbitMQ.ini","notaweatherapp");
=======
require_once('/ComponentsofRabbitClient/path.inc');
require_once('/ComponentsofRabbitClient/get_host_info.inc');
require_once('/ComponentsofRabbitClient/rabbitMQLib.inc');

$client = new rabbitMQClient("testRabbitMQ.ini","testServer");
>>>>>>> 26247c39a6a644b4f70738d5e592b41719062489

if (isset($argv[1]) && isset($argv[2]))
{
  $username = $argv[1];
  $password = $argv[2];
}
else
{
  echo "Usage: " . $argv[0] . " <username> <password>\n";
  exit(1);
}

$request = array();
$request['type'] = "Login";
$request['username'] = $username;
$request['password'] = $password;
$request['message'] = "Requesting login";

$response = $client->send_request($request);
//$response = $client->publish($request);

echo "Client received response: ".PHP_EOL;
print_r($response);
echo "\n\n";
<<<<<<< HEAD
echo PHP_EOL;

if (isset($response['session_id']))
{
    $sessionId = $response['session_id'];
=======

if ($response === true)
{
    $sessionId = $_SESSION['session_id'];
>>>>>>> 26247c39a6a644b4f70738d5e592b41719062489
    echo "Login successful! Session ID: " . $sessionId . PHP_EOL; 

    $validateRequest = array();
    $validateRequest['type'] = "validate_session";
    $validateRequest['session_id'] = $sessionId;

    $validateResponse = $client->send_request($validateRequest);
    echo "Session validation response: ".PHP_EOL;
    print_r($validateResponse);
}
else
{
    echo "Login failed. Please check your credentials." . PHP_EOL;
}

<<<<<<< HEAD
unset($client);

echo $argv[0]." END".PHP_EOL;
=======
echo $argv[0]." END".PHP_EOL;
>>>>>>> 26247c39a6a644b4f70738d5e592b41719062489
