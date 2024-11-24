<!DOCTYPE html>
<html>
<head>
    <title>Nick's Sample</title>
</head>
<body>
<?php include('header.php'); ?>
<main>
    <h1>Login</h1>
    <p>Welcome! Please login.</p>

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
                        echo "<p>Login failed. Please check your credentials.</p>";
                    }
                } 
                else 
                {
                    echo "<p>Error: Could not start login process.</p>";
                }
            } 
            else 
            {
                echo "<p>Please fill in both username and password.</p>";
            }
        }
        ?>
    </div>

    <!-- Login form -->
    <form action="login.php" method="POST">
        <input type="text" name="username" placeholder="Username" required>
        <input type="password" name="password" placeholder="Password" required>
        <input type="submit" value="Login">
    </form>
</main>
</body>
</html>
