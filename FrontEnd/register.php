<?php

require_once 'testRabbitMQClient.php';


$username = trim($_POST["username"]);
$password = trim($_POST["password"]);


$data = [
	'username' => $username,
	'password' => $hashed_password,
];

$exchange = 'IT490_exchange';
$queue = 'Registration';

$channel->queue_declare($queue, false, true, false, false);
$channel->exchange_declare($exchange, 'direct', false, true, false);
$channel->queue_bind($queue, $exchange);

$msg = new AMQPMessage(json_encode($data), ['delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT]);
$channel->basic_publish($msg, $exchange);

$channel->close();
$conn->close();

echo "Registration request sent!";
?>
