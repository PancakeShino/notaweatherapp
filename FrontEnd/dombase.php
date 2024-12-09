<html>
<body>
<?php 
    $username = "dom"; 
    $password = "Aviator@1337"; 
    $dsn = 'mysql:host=localhost;dbname=login';
    $query = "SELECT user_id, username, password FROM users";

    try {
        $db = new PDO($dsn, $username, $password);
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        echo '<p>You have successfully connected to the database!</p>';

        $statement = $db->query($query);

        $users = $statement->fetchAll(PDO::FETCH_ASSOC);

        if (count($users) > 0) {
            echo "<table border='1'>";
            echo "<tr><th>User ID</th><th>Username</th><th>Password</th></tr>";

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
        $error_message = $exception->getMessage();
        echo '<p>Connection failed: ' . $error_message . '</p>';
    }
?>
</body>
</html>
