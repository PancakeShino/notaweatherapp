#!/usr/bin/php
<?php
require_once('path.inc');
require_once('get_host_info.inc');
require_once('rabbitMQLib.inc');

function doLogin($username,$password)
{
    $mysqli = new mysqli("10.243.120.72", getenv('notaweatherapp'), getenv('1234'), "IT490");

    if ($mysqli->connect_error)
    {
        return false;
    }

    $stmt = $mysqli->prepare("SELECT password FROM Users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows == 0)
    {
        return false;
    }

    $stmt->bind_result($stored_password);
    $stmt->fetch();

    if (password_verify($password, $stored_password))
    {
        $sessionId = bin2hex(random_bytes(32));
        $_SESSION['username'] = $username;
        $_SESSION['session_id'] = hash ('sha256', $sessionId);

        storeSessionInDatabase($username, $_SESSION['session_id']);

        return true;
    }
    else
    {
        return false;
    }
}

function storeSessionInDatabase($username, $hashedSessionId)
{
    $mysqli = new mysqli("10.243.120.72", "notaweatherapp", "1234", "IT490");
    $stmt = $mysqli->prepare("INSERT INTO sessions (username, session_id) VALUES (?, ?) ON DUPLICATE KEY UPDATE session_id = ?");
    $stmt->bind_param("sss", $username, $hashedSessionId, $hashedSessionId);
    $stmt->execute();
}

function requestProcessor($request)
{
  echo "received request".PHP_EOL;
  var_dump($request);

  //Check if a request sent by client is valid
  if(!isset($request['session_id']) || !validateSession($request['session_id']))
  {
    return "ERROR: invalid session";
  }

  if (!isset($request['type']))
  {
    return "ERROR: unsupported messagfe type";
  }

  switch ($request['type'])
  {
    case "login":
      return doLogin($request['username'],$request['password']);
    case "validate_session":
      return doValidate($request['sessionId']);
  }
  return array("returnCode" => '0', 'message'=>"Server received request and processed");
}

function  validateSession($sessionId)
{
    $mysqli = new mysqli("10.243.120.72", "notaweatherapp", '1234', "IT490");
    $stmt = $mysqli->prepare("SELECT username FROM sessions WHERE session_id = ?");
    $stmt->bind_param("s", $sessionId);
    $stmt->execute();
    $stmt->store_result();

    return $stmt->num_rows > 0;
}
?>