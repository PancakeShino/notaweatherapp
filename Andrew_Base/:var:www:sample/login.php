<?php

if (empty($_POST)) {
    $msg = "NO POST MESSAGE SET, POLITELY F*** OFF";
    echo json_encode($msg);
    exit(0);
}

$request = $_POST;
$response = "unsupported request type, politely F*** OFF";

switch ($request["type"]) {
    case "login":
        $response = "login, yeah we can do that";
        break;
}

echo json_encode($response);
exit(0);

?>