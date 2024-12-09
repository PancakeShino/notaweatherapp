<?php 
session_start();
// Clear session data or destroy the session
$_SESSION = []; // Clear the session data
session_destroy(); // Destroy the session

// return user to home page after a few seconds.
echo "<p>You have been logged out. Redirecting to the home page in 5 seconds...</p>";

echo "<script>
    setTimeout(function() {
        window.location.href = 'home.php';
    }, 5000); // 5000 milliseconds = 5 seconds
</script>";

?>