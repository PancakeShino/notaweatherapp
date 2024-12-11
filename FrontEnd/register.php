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
                    <div class="card-header bg-info text-white text-center">
                        <h1 class="h4">Register</h1>
                    </div>
                    <div class="card-body">
                        <p class="text-center">Welcome! Please register to use our website.</p>

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
                                            echo "<div class='alert alert-danger'>Registration failed.</div>";
                                        }
                                    } 
                                    else 
                                    {
                                        echo "<div class='alert alert-danger'>Error: Could not start registration process.</div>";
                                    }
                                } 
                                else
                                {
                                    echo "<div class='alert alert-warning'>Please enter a username and password.</div>";
                                }
                            }
                            ?>
                        </div>

                        <!-- Register form -->
                        <form action="register.php" method="POST">
                            <div class="mb-3">
                                <label for="username" class="form-label">Username</label>
                                <input type="text" id="username" name="username" class="form-control" placeholder="Enter your username" required>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" id="password" name="password" class="form-control" placeholder="Enter your password" required>
                            </div>
                            <div class="d-grid">
                                <button type="submit" class="btn btn-info text-white">Register</button>
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
