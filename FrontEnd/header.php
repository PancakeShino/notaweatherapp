<header>
  <div class="topnav">
    <?php 
    session_start();
    if (isset($_SESSION['validLogin']) && $_SESSION['validLogin'] && isset($_SESSION['session_id'])) { 
    ?>
      <p style="text-align:center">
        <!-- <p>Hello, <?php echo htmlspecialchars($_SESSION['username']); ?>!</p> -->
        <a href="home.php">Home</a>
        <br>
        <a href="search.php">Artist Search</a>
        <br>
        <a href="events.php">Event Search</a>
        <br>
        <a href="map.php">Map (debug)</a>
        <br>
        <a href="secret.php">Secret</a>
        <br><br>
        <a href="logout.php">Logout</a>
      </p>
    <?php } else { ?>
      <p style="text-align:center">
        <a href="home.php">Home</a>
        <br>
        <a href="search.php">Artist Search</a>
        <br>
        <a href="events.php">Event Search</a>
        <br>
        <a href="map.php">Map (debug)</a>
        <br><br>
        <a href="login.php">Login</a>
        <br>
        <a href="register.php">Register</a>
      </p>
    <?php  } ?>
  </div>
</header>
