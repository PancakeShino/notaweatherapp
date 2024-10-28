#!/usr/bin/php
<?php
require_once('/ComponentsofRabbitClient/path.inc');
require_once('/ComponentsofRabbitClient/get_host_info.inc');
require_once('/ComponentsofRabbitClient/rabbitMQLib.inc');

$client = new rabbitMQClient("testRabbitMQ.ini","testServer");

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

if ($response === true)
{
    $sessionId = $_SESSION['session_id'];
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

echo $argv[0]." END".PHP_EOL;