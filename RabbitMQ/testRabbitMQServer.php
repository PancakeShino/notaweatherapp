#!/usr/bin/php
<?php
<<<<<<< HEAD
require_once('ComponentsofRabbitClient/path.inc');
require_once('ComponentsofRabbitClient/get_host_info.inc');
require_once('ComponentsofRabbitClient/rabbitMQLib.inc');

function doLogin($username,$password)
{
    $mysqli = new mysqli("10.243.120.72", "notaweatherapp", "1234", "IT490");

    if ($mysqli->connect_error)
    {
        return array("returnCode" => '1', 'message'=>"Database connection failed.");
=======
require_once('path.inc');
require_once('get_host_info.inc');
require_once('rabbitMQLib.inc');

function doLogin($username,$password)
{
    $mysqli = new mysqli("10.243.120.72", getenv('notaweatherapp'), getenv('1234'), "IT490");

    if ($mysqli->connect_error)
    {
        return false;
>>>>>>> 26247c39a6a644b4f70738d5e592b41719062489
    }

    $stmt = $mysqli->prepare("SELECT password FROM Users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows == 0)
    {
<<<<<<< HEAD
        return array("returnCode" => '1', 'message'=>"Invalid username.");
=======
        return false;
>>>>>>> 26247c39a6a644b4f70738d5e592b41719062489
    }

    $stmt->bind_result($stored_password);
    $stmt->fetch();

    if (password_verify($password, $stored_password))
    {
<<<<<<< HEAD
	    $sessionId = bin2hex(random_bytes(32));
	    $expiresAt = date("Y-m-d H:i:s", strtotime('+1 hour'));

	    storeSessionInDatabase($username, $sessionId, $expiresAt);
	    return array("returnCode" => '0', 'message' => "Login successful", 'session_id' => $sessionId);
    }
    else
    {
        return array("returnCode" => '1', 'message'=>"Invalid password.");
    }
}

function storeSessionInDatabase($username, $sessionId, $expiresAt)
{
    $mysqli = new mysqli("10.243.120.72", "notaweatherapp", "1234", "IT490");

    if ($mysqli->connect_error) {
        return array("returnCode" => '1', 'message' => "Database connection failed.");
    }
    
    // Correct the column name by using backticks
    $stmt = $mysqli->prepare("INSERT INTO sessions (session_id, username, `expires_at`) VALUES (?, ?, ?)");
    
    // Bind parameters: session_id, username, expires_at
    $stmt->bind_param("sss", $sessionId, $username, $expiresAt);

    // Execute the statement
    if ($stmt->execute()) {
        return array("returnCode" => '0', 'message' => "Session stored successfully.");
    } else {
        return array("returnCode" => '1', 'message' => "Error storing session: " . $stmt->error);
    }
}


function requestProcessor($request)
{
  echo "received request". json_encode($request) . PHP_EOL;
  
  if(!isset($request['type'])) 
  {
    return array("returnCode" => '1', 'message' => "ERROR: unsupported message type");
  }

  switch($request['type'])
  {
    case "Login":
        return doLogin($request['username'], $request['password']);
    case "validate_session":
	    if (validateSession($request['session_id']))
	    {
		 return array("returnCode" => '0', 'message' => "Session is valid.");
	    }
	    else
	    {
		  return array("returnCode" => '1', 'message' => "Sessions is invalid.");
	    } 
    default:
      return array("returnCode" => '1', 'message' =>"ERROR: unsupported message type");
  }
=======
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
>>>>>>> 26247c39a6a644b4f70738d5e592b41719062489
}

function  validateSession($sessionId)
{
    $mysqli = new mysqli("10.243.120.72", "notaweatherapp", '1234', "IT490");
<<<<<<< HEAD
    $stmt = $mysqli->prepare("SELECT username FROM sessions WHERE session_id = ? AND expires_at > NOW()");
=======
    $stmt = $mysqli->prepare("SELECT username FROM sessions WHERE session_id = ?");
>>>>>>> 26247c39a6a644b4f70738d5e592b41719062489
    $stmt->bind_param("s", $sessionId);
    $stmt->execute();
    $stmt->store_result();

    return $stmt->num_rows > 0;
}
<<<<<<< HEAD

$server = new rabbitMQServer("testRabbitMQ.ini", "notaweatherapp");
$server->process_requests('requestProcessor');
?>
=======
?>
>>>>>>> 26247c39a6a644b4f70738d5e592b41719062489
