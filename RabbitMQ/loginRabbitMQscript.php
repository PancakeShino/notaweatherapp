<?php
require_once 'testRabbitMQClient.php';
require_once 'dombase.php'; // Database connection file

use PhpAmqpLib\Message\AMQPMessage;

$channel->queue_declare('Login', false, true, false, false);

$callback = function ($msg) use ($conn) {
    $data = json_decode($msg->body, true);
    $username = $data['username'];
    $password = $data['password'];

   
    $stmt = $conn->prepare("SELECT password FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->bind_result($stored_password);
    $stmt->fetch();

    if ($stored_password && $password === $stored_password) {
        echo "Login successful.\n";
    } else {
        echo "Invalid username or password.\n";
    }

    $stmt->close();

    $msg->delivery_info['channel']->basic_ack($msg->delivery_info['delivery_tag']);
};

$channel->basic_consume('Login', '', false, true, false, false, $callback);

echo "Waiting for login requests...\n";
while ($channel->is_consuming()) {
    $channel->wait();
}

$channel->close();
$conn->close();
?>
