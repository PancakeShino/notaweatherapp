<?php

require_once 'testRabbitMQClient.php';


$username = trim($_POST["username"]);
$password = trim($_POST["password"]);

$hashed_password = password_hash($password, PASSWORD_DEFAULT);


$data = [
	'username' => $username,
	'password' => $hashed_password,
];

$exchange = 'registration_exchange';
$queue = 'registration_queue';

$channel->queue_declare($queue, false, true, false, false);
$channel->exchange_declare($exchange, 'direct', false, true, false);
$channel->queue_bind($queue, $exchange);

$channel->close();
$conn->close();

echo "Registration request sent! Please wait for confirmation. ";
?>
