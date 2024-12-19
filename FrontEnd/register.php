<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <script>
        function validatePasswords() {
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('confirmPassword').value;
            const message = document.getElementById('passwordMessage');

            if (password !== confirmPassword) {
                message.style.display = 'block';
                return false;
            } else {
                message.style.display = 'none';
                return true;
            }
        }
    </script>
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

                            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                                if (!empty($_POST['username']) && !empty($_POST['password'])) {
                                    $username = escapeshellarg($_POST['username']);
                                    $password = escapeshellarg($_POST['password']);

                                    $command = "php ../RabbitMQ/RabbitMQRegister.php $username $password";

                                    $descriptorspec = [
                                        1 => ["pipe", "w"],
                                        2 => ["pipe", "w"]
                                    ];

                                    $process = proc_open($command, $descriptorspec, $pipes);

                                    if (is_resource($process)) {
                                        $timeout = 5;
                                        $start = time();
                                        $outputText = '';

                                        stream_set_blocking($pipes[1], false);
                                        while (time() - $start < $timeout) {
                                            $outputText .= stream_get_contents($pipes[1]);
                                            if (feof($pipes[1])) {
                                                break;
                                            }
                                            usleep(100000);
                                        }

                                        fclose($pipes[1]);
                                        fclose($pipes[2]);
                                        proc_close($process);

                                        if (strpos($outputText, 'Registration successful!') !== false) {
                                            echo "<div class='alert alert-success'>Registration successful! Redirecting to home...</div>";
                                            echo "<script>setTimeout(() => { window.location.href = 'home.php'; }, 3000);</script>";
                                        } elseif (strpos($outputText, 'Username already exists.') !== false) {
                                            echo "<div class='alert alert-danger'>Username already exists. Please choose a different username.</div>";
                                        } elseif (strpos($outputText, 'Database connection failed') !== false) {
                                            echo "<div class='alert alert-danger'>There was an issue connecting to the database. Please try again later.</div>";
                                        } else {
                                            echo "<div class='alert alert-danger'>Registration failed. Please try again.</div>";
                                        }
                                    } else {
                                        echo "<div class='alert alert-danger'>Error: Could not start registration process.</div>";
                                    }
                                } else {
                                    echo "<div class='alert alert-warning'>Please enter a username and password.</div>";
                                }
                            }
                            ?>
                        </div>

                        <form action="register.php" method="POST" onsubmit="return validatePasswords()">
                            <div class="mb-3">
                                <label for="username" class="form-label">Username</label>
                                <input type="text" id="username" name="username" class="form-control" placeholder="Enter your username" required>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" id="password" name="password" class="form-control" placeholder="Enter your password" required>
                            </div>
                            <div class="mb-3">
                                <label for="confirmPassword" class="form-label">Confirm Password</label>
                                <input type="password" id="confirmPassword" class="form-control" placeholder="Confirm your password" required>
                                <small id="passwordMessage" class="text-danger" style="display: none;">Passwords do not match.</small>
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

    <?php include('footer.php'); ?>
    <!-- Bootstrap JS -->
    <script src="js/bootstrap.bundle.min.js"></script>
</body>
</html>
