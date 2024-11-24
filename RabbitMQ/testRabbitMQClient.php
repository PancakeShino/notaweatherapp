<?php
require_once 'testRabbitMQClient.php';

use PhpAmqpLib\Message\AMQPMessage;

// Declare the registration queue
$channel->queue_declare('Registration', false, true, false, false);

$callback = function ($msg) use ($conn) {
    $data = json_decode($msg->body, true);
    $username = $data['username'];
    $password = $data['password'];

    try {
        // Check if username exists
        $stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $response = [
                'status' => 'error',
                'message' => 'Username already taken.'
            ];
        } else {
            // Insert new user
            $stmt->close(); // Close the previous statement
            $stmt = $conn->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
            $stmt->bind_param("ss", $username, $password);
            $stmt->execute();

            $response = [
                'status' => 'success',
                'message' => 'User registered successfully!'
            ];
        }
        $stmt->close();
    } catch (Exception $e) {
        $response = [
            'status' => 'error',
            'message' => $e->getMessage()
        ];
    }

    // Send response back
    $msg->delivery_info['channel']->basic_ack($msg->delivery_info['delivery_tag']);
    echo json_encode($response) . PHP_EOL;
};

// Consume registration messages
$channel->basic_consume('Registration', '', false, false, false, false, $callback);

echo "Waiting for registration requests...\n";
while ($channel->is_consuming()) {
    $channel->wait();
}

$channel->close();
$conn->close();
?>
