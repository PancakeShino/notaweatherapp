#!/usr/bin/php
<?php
require_once('ComponentsofRabbitClient/path.inc');
require_once('ComponentsofRabbitClient/get_host_info.inc');
require_once('ComponentsofRabbitClient/rabbitMQLib.inc');

$client = new rabbitMQClient("testRabbitMQ.ini", "notaweatherapp");

if (isset($argv[1])) {
    $sessionId = $argv[1];

    $request = [
        "type" => "validate_session",
        "session_id" => $sessionId,
    ];

    $response = $client->send_request($request);
    echo json_encode($response);
} else {
    echo json_encode(["returnCode" => '1', "message" => "Session ID not provided."]);
}
?>
