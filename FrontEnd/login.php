<!DOCTYPE html>
<html>
<head>
    <title>Nick's Sample</title>
</head>

<body>
    <main>
        <!-- Nick Malefyt. IT 490 10/26/2024 -->
<header>
<!--Setlist.fm here if we do that as out project API KEY: pEhpqLvlgcuitpMIPpAjWU-iqFQbnVUiKKKr -->    

<div id="textResponse">
        <?php
        if ($_SERVER["REQUEST_METHOD"] == "POST")
        {
            if (!empty($_POST['username']) && !empty($_POST['password']))
            {
                $username = $_POST['username'];
                $password = $_POST['password'];

                $output = [];
                $return_var = 0;

                //Replace authentication with RabbitMQ
                exec("php ../RabbitMQ/testRabbitMQClient.php $username $password", $output, $return_var);
                
                echo implode("\n", $output);

                if ($return_var === 0)
                {
                    echo "Login request send seccssfully.";
                } 
                else 
                {
                    echo "Failed to send login request";
                }
            } 
            else 
            {
                echo "Please fill in both username and password.";
            }
            //End of authentication section with RabbitMQ (Modify this space, DONT REMOVE THESE COMMENTS)
        }
        ?>
    </div>

<div class="topnav">
    <br>
    <p>
      <a href="home.php">Home</a>
      <br>
      <a href="login.php">Login</a>
      <br>
      <a href="register.html">Register</a>
    </p>
        </div>
</header>    <h1>Login Page</h1>
    <p>Welcome! Please login below.</p>
<!-- Add header and footer eventually. -->
    <div id="textResponse">
            </div>

    <!-- Login form -->
    <form action="login.php" method="POST">
        <input type="text" name="username" placeholder="Username" required>
        <input type="password" name="password" placeholder="Password" required>
        <input type="submit" value="Login">
    </form>

    <!-- Link to registration page -->
    <a href="register.html">Link</a>
    </main>

</body>
</html>
