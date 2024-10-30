<!DOCTYPE html>
<html>
<head>
    <title>Andrew's Sample</title>
</head>

<body>
    <h1>Andrew's Sample</h1>
    <p>This is a test website to learn how to use Apache2 in my IT490 project.</p>
    
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

    <!-- Login form -->
    <form action="login.php" method="POST">
        <input type="text" name="username" placeholder="Username" required>
        <input type="password" name="password" placeholder="Password" required>
        <input type="submit" value="Login">
    </form>

</body>
</html>
