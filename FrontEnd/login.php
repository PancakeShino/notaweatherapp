<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nick's Sample</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <?php include('header.php'); ?>

    <main class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white text-center">
                        <h1 class="h4">Login</h1>
                    </div>
                    <div class="card-body">
                        <p class="text-center">Welcome! Please login.</p>

                        <div id="textResponse">
                            <?php
                            session_start();

                            // Ensure a new session ID is generated for each session
                            session_regenerate_id(true);

                            if ($_SERVER["REQUEST_METHOD"] === "POST") 
                            {
                                if (!empty($_POST['username']) && !empty($_POST['password'])) 
                                {
                                    $username = escapeshellarg($_POST['username']); // Escape shell arguments securely
                                    $password = escapeshellarg($_POST['password']);

                                    // Command to execute RabbitMQ client script
                                    $command = "php ../RabbitMQ/testRabbitMQClient.php $username $password";

                                    // Descriptor for pipes
                                    $descriptorspec = [
                                        1 => ["pipe", "w"],  // Stdout
                                        2 => ["pipe", "w"]   // Stderr
                                    ];

                                    // Open process
                                    $process = proc_open($command, $descriptorspec, $pipes);

                                    if (is_resource($process)) 
                                    {
                                        $timeout = 5; // Set timeout in seconds
                                        $start = time();
                                        $outputText = '';

                                        // Set non-blocking stream for stdout
                                        stream_set_blocking($pipes[1], false);

                                        // Read the output within the timeout
                                        while (time() - $start < $timeout) 
                                        {
                                            $outputText .= stream_get_contents($pipes[1]);
                                            if (feof($pipes[1])) 
                                            {
                                                break;
                                            }
                                            usleep(100000); // Sleep for 100ms
                                        }

                                        // Close the pipes
                                        fclose($pipes[1]);
                                        fclose($pipes[2]);

                                        // Terminate process if still running after timeout
                                        $status = proc_get_status($process);
                                        if ($status['running'] === true) 
                                        {
                                            proc_terminate($process);
                                        }

                                        proc_close($process);

                                        // Check login result
                                        if (strpos($outputText, 'Login successful') !== false) 
                                        {
                                            header("Location: success.php"); // Redirect to success page
                                            exit();
                                        } 
                                        else 
                                        {
                                            echo "<div class='alert alert-danger'>Login failed. Please check your credentials.</div>";
                                        }
                                    } 
                                    else 
                                    {
                                        echo "<div class='alert alert-danger'>Error: Could not start login process.</div>";
                                    }
                                } 
                                else 
                                {
                                    echo "<div class='alert alert-warning'>Please fill in both username and password.</div>";
                                }
                            }
                            ?>
                        </div>

                        <!-- Login form -->
                        <form action="login.php" method="POST">
                            <div class="mb-3">
                                <label for="username" class="form-label">Username</label>
                                <input type="text" id="username" name="username" class="form-control" placeholder="Enter your username" required>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" id="password" name="password" class="form-control" placeholder="Enter your password" required>
                            </div>
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary">Login</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
