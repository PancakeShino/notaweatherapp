<?php
require_once 'testRabbitMQClient.php';

use PhpAmqpLib\Message\AMQPMessage;

$channel->queue_declare('Registration', false, true, false, false);

$callback = function($msg) use ($conn) {
    $data = json_decode($msg->body, true);
    $username = $data['username'];
    $password = $data['password'];

    
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param("s", $username,);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        echo "Username is already taken.";
    } else {
        // Insert new user
        $stmt = $conn->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
        $stmt->bind_param("ss", $username, $password);
        $stmt->execute();
        echo "User registered successfully!";
    }
    $stmt->close();

    
    $msg->delivery_info['channel']->basic_ack($msg->delivery_info['delivery_tag']);
};

$channel->basic_consume('Registration', '', false, false, false, false, $callback);

echo "Waiting for login requests...\n";
while ($channel->is_consuming()) {
    $channel->wait();
}

$channel->close();
$conn->close();
?>
