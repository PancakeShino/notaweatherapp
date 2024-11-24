<html>
<body>
<?php 
$username = "dom"; 
$password = "Aviator@1337"; 
$dsn = 'mysql:host=localhost;dbname=login';

// Define your SQL query
$query = "SELECT user_id, username, password FROM users";

try {
    // Connect to the database using PDO
    $db = new PDO($dsn, $username, $password);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo '<p>You have successfully connected to the database!</p>';

    // Execute the query
    $statement = $db->query($query);

    // Fetch all the results as an associative array
    $users = $statement->fetchAll(PDO::FETCH_ASSOC);

    // Check if any data was returned
    if (count($users) > 0) {
        echo "<table border='1'>";
        echo "<tr><th>User ID</th><th>Username</th><th>Password</th></tr>";

        // Loop through and display each user
        foreach ($users as $row) {
            echo '<tr>';
            echo '<td>' . $row['user_id'] . '</td>';
            echo '<td>' . $row['username'] . '</td>';
            echo '<td>' . $row['password'] . '</td>';
            echo '</tr>';
        }
        echo "</table>";
    } else {
        echo "<p>No users found in the database.</p>";
    }

} catch(PDOException $exception) {
    // Handle connection errors
    $error_message = $exception->getMessage();
    echo '<p>Connection failed: ' . $error_message . '</p>';
}
?>
</body>
</html>
