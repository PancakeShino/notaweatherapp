<?php

require_once 'testRabbitMQClient.php';


$username = trim($_POST["username"]);
$password = trim($_POST["password"]);

$data = [
	'username' => $username,
	'password' => $password,
];

$exchange = 'IT490_exchange';
$queue = 'Registration';

$channel->queue_declare($queue, false, true, false, false);
$channel->exchange_declare($exchange, 'direct', false, true, false);
$channel->queue_bind($queue, $exchange);

$channel->close();
$conn->close();

echo "Registration request sent! Please wait for confirmation. ";
?>
