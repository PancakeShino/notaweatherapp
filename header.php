<!-- Nick Malefyt. IT 490 10/26/2024 -->
<header>
<!--Setlist.fm API KEY: pEhpqLvlgcuitpMIPpAjWU-iqFQbnVUiKKKr -->    
  <div class="topnav">
<!--<img src="images/image." height="75"/>-->
  <br>

    <!-- if usr logged in.-->
    <div class="topnav">
    <?php if (isset($_SESSION['validLogin'])) { ?> 
        <p>Hello, <?php echo $_SESSION['username']; ?></p>

        <p>
            <a href="home.php">Home</a>
            <br>
            <a href="login.php">Login</a>
            <br>
            <a href="logout.php">Logout</a>
        </p>

    <?php } else { ?>
        <p>
            <a href="home.php">Home</a>
            <br>
            <a href="login.php">Login</a>
            <br>
            <a href="register.html">Register</a>
        </p>
    <?php } ?> 
</div>
</header>