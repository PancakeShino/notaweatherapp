<html>
<head>
    <title>Nick's Sample</title>
</head>
<body>
<?php include('header.php'); ?>
<main>
    <h1>Register</h1>
    <p>Welcome! Please register to use our website.</p>

    <div id="textResponse">
        <?php
        session_start();

        if ($_SERVER["REQUEST_METHOD"] == "POST")
        {
            if (!empty($_POST['username']) && !empty($_POST['password']))
            {
                $username = escapeshellarg($_POST['username']);
                $password = escapeshellarg($_POST['password']);

                $command = "php ../RabbitMQ/testRabbitMQRegister.php $username $password";

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

                    if (strpos($outputText, 'You have successfully registered. Welcome!') !== false) 
                    {
                        header("Location: success.php");
                        exit();
                    } 
                    else 
                    {
                        echo "<p>Registration failed.</p>";
                    }
                } 
                else 
                {
                    echo "<p>Error: Could not start registration process.</p>";
                }
            } 
            else
            {
                echo "<p>Please enter a username and password.</p>";
            }
        }
        ?>
    </div>

    <!--Register form-->
    <form action="registertest.php" method="POST">
        <input type="text" name="username" placeholder="Username" required>
        <input type="password" name="password" placeholder="Password" required>
        <input type="submit" value="Register">
    </form>
    </main>
</body>
</html>