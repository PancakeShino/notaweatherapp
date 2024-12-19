#!/usr/bin/php
<?php
require_once('ComponentsofRabbitClient/path.inc');
require_once('ComponentsofRabbitClient/get_host_info.inc');
require_once('ComponentsofRabbitClient/rabbitMQLib.inc');

function doLogin($username, $password)
{
    $mysqli = new mysqli("10.243.120.72", "notaweatherapp", "1234", "IT490");

    if ($mysqli->connect_error) {
        return array("returnCode" => '1', 'message' => "Database connection failed.");
    }

    $stmt = $mysqli->prepare("SELECT password FROM Users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows == 0) {
        return array("returnCode" => '1', 'message' => "Invalid username.");
    }

    $stmt->bind_result($stored_password);
    $stmt->fetch();

    echo "Stored password hash: $stored_password" . PHP_EOL;
    echo "User entered password: $password" . PHP_EOL;

    if (password_verify($password, $stored_password)) {
        echo "Password verified successfully!" . PHP_EOL;

        $sessionId = bin2hex(random_bytes(32));
        $expiresAt = date("Y-m-d H:i:s", strtotime('+1 hour'));
        storeSessionInDatabase($username, $sessionId, $expiresAt);

        return array("returnCode" => '0', 'message' => "Login successful", 'session_id' => $sessionId);
    } else {
        echo "Password verification failed." . PHP_EOL;
        return array("returnCode" => '1', 'message' => "Invalid password.");
    }
}


function doRegister($username, $password) 
{
    $mysqli = new mysqli("10.243.120.72", "notaweatherapp", "1234", "IT490");
    
    if ($mysqli->connect_error) {
        return array("returnCode" => '1', 'message' => "Database connection failed: " . $mysqli->connect_error);
    }

    $stmt = $mysqli->prepare("SELECT username FROM Users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->close();
        return array("returnCode" => '1', 'message' => "Username already exists.");
    }
    $stmt->close();

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    
    $stmt = $mysqli->prepare("INSERT INTO Users (username, password) VALUES (?, ?)");
    $stmt->bind_param("ss", $username, $hashed_password);

    if ($stmt->execute()) {
        $stmt->close();
        $mysqli->close();
        return array("returnCode" => '0', 'message' => "Registration successful!");
    } else {
        $stmt->close();
        $mysqli->close();
        return array("returnCode" => '1', 'message' => "Registration failed: " . $stmt->error);
    }
}


function storeSessionInDatabase($username, $sessionId, $expiresAt)
{
    $mysqli = new mysqli("10.243.120.72", "notaweatherapp", "1234", "IT490");

    if ($mysqli->connect_error) {
        return array("returnCode" => '1', 'message' => "Database connection failed.");
    }
    
    $stmt = $mysqli->prepare("INSERT INTO sessions (session_id, username, `expires_at`) VALUES (?, ?, ?)");
    
    $stmt->bind_param("sss", $sessionId, $username, $expiresAt);

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
    case "Registration":
        return doRegister($request['username'], $request['password']);    
    case "toggle_follow_artist":
        return toggleFollowArtist($request['username'], $request['artist_id']); 
    case "get_followed_artists":
        return getFollowedArtists($request['username']);
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
}

function validateSession($sessionId)
{
    $mysqli = new mysqli("10.243.120.72", "notaweatherapp", '1234', "IT490");
    $stmt = $mysqli->prepare("SELECT username FROM sessions WHERE session_id = ? AND expires_at > NOW()");
    $stmt->bind_param("s", $sessionId);
    $stmt->execute();
    $stmt->store_result();

    return $stmt->num_rows > 0;
}

function getFollowedArtists($username)
{
    $mysqli = new mysqli("10.243.120.72", "notaweatherapp", "1234", "IT490");

    if ($mysqli->connect_error) {
        return array("success" => false, "message" => "Database connection failed.");
    }

    // Get user ID from username
    $stmt = $mysqli->prepare("SELECT id FROM Users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    
    if (!$user) {
        return array("success" => false, "message" => "User not found");
    }

    // Fetch followed artists
    $stmt = $mysqli->prepare("SELECT artist_id FROM followed_artists WHERE user_id = ?");
    $stmt->bind_param("i", $user['id']);
    $stmt->execute();
    $result = $stmt->get_result();

    $artistIds = [];
    while ($row = $result->fetch_assoc()) {
        $artistIds[] = $row['artist_id'];
    }

    return array("success" => true, "artists" => $artistIds);
}


function toggleFollowArtist($username, $artistId) {
    $mysqli = new mysqli("10.243.120.72", "notaweatherapp", "1234", "IT490");
    
    if ($mysqli->connect_error) {
        return array("success" => false, "message" => "Database connection failed");
    }

    // Get user_id from username
    $stmt = $mysqli->prepare("SELECT id FROM Users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    
    if (!$user) {
        return array("success" => false, "message" => "User not found");
    }

    // Check if already following
    $stmt = $mysqli->prepare("SELECT id FROM followed_artists WHERE user_id = ? AND artist_id = ?");
    $stmt->bind_param("is", $user['id'], $artistId);
    $stmt->execute();
    $stmt->store_result();
    
    if ($stmt->num_rows > 0) {
        // Unfollow
        $stmt = $mysqli->prepare("DELETE FROM followed_artists WHERE user_id = ? AND artist_id = ?");
        $stmt->bind_param("is", $user['id'], $artistId);
        $success = $stmt->execute();
        return array("success" => $success, "message" => $success ? "Unfollowed successfully" : "Error unfollowing", "following" => false);
    } else {
        // Follow
        $stmt = $mysqli->prepare("INSERT INTO followed_artists (user_id, artist_id) VALUES (?, ?)");
        $stmt->bind_param("is", $user['id'], $artistId);
        $success = $stmt->execute();
        return array("success" => $success, "message" => $success ? "Followed successfully" : "Error following", "following" => true);
    }
}

$server = new rabbitMQServer("testRabbitMQ.ini", "notaweatherapp");
$server->process_requests('requestProcessor');

?>
