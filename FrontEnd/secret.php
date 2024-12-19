<?php
session_start();
if (!isset($_SESSION['validLogin']) || !$_SESSION['validLogin'] || !isset($_SESSION['session_id'])) {
    header("Location: login.php");
    exit();
}?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Secret Page</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
</head>
<body>
    <?php include('header.php'); ?>
    <main class="container mt-5">
        <h1>Welcome to the secret page ;P</h1>
        <h2>Hello, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h2>
    </main>

    <?php include('footer.php'); ?>
</body>
</html>
