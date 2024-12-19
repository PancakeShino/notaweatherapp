<?php
header('Content-Type: application/json');
ob_start();

session_start();
require_once('../RabbitMQ/ComponentsofRabbitClient/path.inc');
require_once('../RabbitMQ/ComponentsofRabbitClient/get_host_info.inc');
require_once('../RabbitMQ/ComponentsofRabbitClient/rabbitMQLib.inc');

if (!isset($_SESSION['validLogin']) || !$_SESSION['validLogin']) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'You are not logged in!']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);
$artist_id = $data['artist_id'] ?? null;
$username = $_SESSION['username'];

if (!$artist_id) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Artist ID is required']);
    exit;
}

$client = new rabbitMQClient("testRabbitMQ.ini", "notaweatherapp");
$request = [
    'type' => 'toggle_follow_artist',
    'username' => $username,
    'artist_id' => $artist_id
];

try {
    $response = $client->send_request($request);

    if (isset($response['success']) && $response['success'] === true) {
        if (isset($response['action']) && $response['action'] === 'followed') {
            echo json_encode([
                'success' => true,
                'action' => 'followed',
                'message' => 'You are now following the artist.'
            ]);
        } elseif (isset($response['action']) && $response['action'] === 'unfollowed') {
            echo json_encode([
                'success' => true,
                'action' => 'unfollowed',
                'message' => 'You have unfollowed the artist.'
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Unexpected response from the server.'
            ]);
        }
    } else {
        echo json_encode([
            'success' => false,
            'message' => $response['message'] ?? 'Failed to toggle follow status.'
        ]);
    }
} catch (Exception $e) {
    error_log('Error while toggling follow status: ' . $e->getMessage());
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'An internal server error occurred.']);
}

$output = ob_get_clean();
if ($output !== '') {
    error_log('Unexpected output: ' . $output);
}
?>