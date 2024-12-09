<!DOCTYPE html>
<html lang="en">
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

        if ($_SERVER["REQUEST_METHOD"] == "POST") 
        {
            if (!empty($_POST['username']) && !empty($_POST['password']))
            {
                $username = escapeshellarg($_POST['username']);
                $password = escapeshellarg($_POST['password']);

                $command = "php ../RabbitMQ/testRabbitMQClient.php $username $password";

                $descriptorspec = [
                    1 => ["pipe", "w"],  
                    2 => ["pipe", "w"] 
                ];

                $process = proc_open($command, $descriptorspec, $pipes);

                if (is_resource($process))
                {
                    $timeout = 5;
                    $start = time();
                    $outputText = '';

                    stream_set_blocking($pipes[1], false);
                    while (time() - $start < $timeout) 
                    {
                        $outputText .= stream_get_contents($pipes[1]);
                        if (feof($pipes[1])) 
                        {
                            break;
                        }
                        usleep(100000);
                    }

                    fclose($pipes[1]);
                    fclose($pipes[2]);

                    $status = proc_get_status($process);
                    if ($status['running'] == true) 
                    {
                        proc_terminate($process);
                    }

                    proc_close($process);

                    if (strpos($outputText, 'Login successful') !== false) 
                    {
                        header("Location: success.php");
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
