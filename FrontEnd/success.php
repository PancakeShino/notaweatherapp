<!-- redundant, deleting soon -->
 
<!DOCTYPE html>
<html>
<head>
    <title>Login Successful</title>
</head>
<body>
    <?php include ('header.php');

    echo"<h1>Success<h1>";
    
   echo "<p>You have been logged in. Redirecting to the home page...</p>";

    echo "<script>
        setTimeout(function() {
            window.location.href = 'home.php';
    }, 1000); // 1000 milliseconds = 1 second
</script>"; ?>

    <main>
        <h1>Login Successful</h1>

    </main>
</body>
</html>