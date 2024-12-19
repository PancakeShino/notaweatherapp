<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="css/bootstrap.min.css">
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

                            // guarantee new session ID each session
                            session_regenerate_id(true);

                            if ($_SERVER["REQUEST_METHOD"] === "POST") 
                            {
                                if (!empty($_POST['username']) && !empty($_POST['password'])) 
                                {
                                    $username = escapeshellarg($_POST['username']);
                                    $password = escapeshellarg($_POST['password']);

                                    $command = "php ../RabbitMQ/RabbitMQLogin.php $username $password";
                                    $descriptorspec = [
                                        1 => ["pipe", "w"],
                                        2 => ["pipe", "w"]
                                    ];

                                    $process = proc_open($command, $descriptorspec, $pipes);

                                    if (is_resource($process)) 
                                    {
                                        $timeout = 10;
                                        $start = time();
                                        $outputText = '';
                                        stream_set_blocking($pipes[1], false);

                                        while (time() - $start < $timeout) 
                                        {
                                            $outputText .= stream_get_contents($pipes[1]);
                                            if (feof($pipes[1])) 
                                            { break; }
                                            usleep(100000);
                                        } 

                                        fclose($pipes[1]);
                                        fclose($pipes[2]);

                                        // terminate process when timeout expires
                                        $status = proc_get_status($process);
                                        if ($status['running'] === true) 
                                        {
                                            proc_terminate($process);
                                        }
                                        proc_close($process);
                                        
                                        if (strpos($outputText, 'Login successful') !== false) {
                                            preg_match('/Session ID: (\w+)/', $outputText, $matches);
                                            if (!empty($matches[1])) {
                                                $_SESSION['session_id'] = $matches[1];
                                                $_SESSION['validLogin'] = true;
                                                $_SESSION['username'] = $_POST['username'];
                                            }
                                            echo "<div class='alert alert-success'>Login successful! Welcome, " . htmlspecialchars($_SESSION['username']) . ". Redirecting you home...</div>";

                                            echo "<script>
                                            setTimeout(function() {
                                                window.location.href = 'home.php';
                                            }, 3000);
                                          </script>";
                                        } else {
                                            echo "<div class='alert alert-danger'>Login failed. oopsie! Please check your credentials and try again.</div>";
                                        }
                                    }
                                } else {
                                    echo "<div class='alert alert-warning'>Please fill in both username and password.</div>";
                                }
                            }
                            ?>
                        </div>

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
                                <button type="submit" class="btn btn-info text-white">Login</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <?php include('footer.php'); ?>
    
    <!-- Bootstrap JS -->
    <script src="js/bootstrap.bundle.min.js"></script>
</body>
</html>
