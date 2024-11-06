<!DOCTYPE html>
<html>
<head>
    <title>Andrew's Sample</title>
</head>
<body>
    <h1>Andrew's Sample</h1>
    <p>Apache2 in my IT490 project.</p>

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
                    $timeout = 2;
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
                        header("Location: success.html");
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

</body>
</html>
