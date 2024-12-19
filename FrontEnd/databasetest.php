<!-- test page to check existing logins in the database -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Database Test</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="css/bootstrap.min.css" >
</head>
<body>
    <div class="container mt-5">
        <div class="card shadow-sm">
            <div class="card-header bg-info text-white">
                <h1 class="h4">Database Connection Test</h1>
            </div>
            <div class="card-body">
                <?php 
                $username = "notaweatherapp"; 
                $password = "1234"; 
                $dsn = 'mysql:host=10.243.120.72;dbname=IT490';

                $query = "SELECT id, username, password FROM Users";

                try {
                    $db = new PDO($dsn, $username, $password);

                    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                    echo '<div class="alert alert-success">You have successfully connected to the database!</div>';

                    $statement = $db->query($query);
                    $users = $statement->fetchAll(PDO::FETCH_ASSOC);

                    if (count($users) > 0) {
                        echo '<table class="table table-striped table-bordered mt-3">';
                        echo '<thead class="table-dark">';
                        echo '<tr><th>User ID</th><th>Username</th><th>Password</th></tr>';
                        echo '</thead><tbody>';

                        foreach ($users as $row) {
                            echo '<tr>';
                            echo '<td>' . htmlspecialchars($row['id']) . '</td>';
                            echo '<td>' . htmlspecialchars($row['username']) . '</td>';
                            echo '<td>' . htmlspecialchars($row['password']) . '</td>';
                            echo '</tr>';
                        } echo '</tbody></table>';
                    } else {
                        echo '<div class="alert alert-warning">No users found in the database.</div>';
                    }
                } catch (PDOException $exception) {
                    $error_message = $exception->getMessage();
                    echo '<div class="alert alert-danger">Connection failed: ' . htmlspecialchars($error_message) . '</div>';
                }
                ?>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="js/bootstrap.bundle.min.js"></script>
</body>
</html>
