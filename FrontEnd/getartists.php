<?php
session_start();
require_once('../RabbitMQ/ComponentsofRabbitClient/path.inc');
require_once('../RabbitMQ/ComponentsofRabbitClient/get_host_info.inc');
require_once('../RabbitMQ/ComponentsofRabbitClient/rabbitMQLib.inc');

if (!isset($_SESSION['validLogin']) || !$_SESSION['validLogin']) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Not authenticated']);
    exit;
}

$client = new rabbitMQClient("testRabbitMQ.ini", "notaweatherapp");
$request = [
    'type' => 'get_followed_artists',
    'username' => $_SESSION['username']
];

$response = $client->send_request($request);
echo json_encode($response);