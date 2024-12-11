<!-- Nick Malefyt. IT 490 10/26/2024 -->
<!--Setlist.fm API KEY: pEhpqLvlgcuitpMIPpAjWU-iqFQbnVUiKKKr -->    

<header>
  <div class="topnav">
    <img src="images/image.jpg" height="75" alt="Logo"/>
    <br>

    <?php
    // Start the session to access session variables
    session_start();
    // Check if the user is logged in 
    if (isset($_SESSION['validLogin']) && $_SESSION['validLogin']) { 
    ?>
      <p>Hello, <?php echo htmlspecialchars($_SESSION['username']); ?>!</p>
      <p>
        <a href="home.php">Home</a>
        <br>
        <!-- Add other pages here -->
        <!--<a href="dashboard.php">Dashboard</a>-->
        <!--<br>-->
        <!--<a href="events.php">Events</a>-->
        <!--<br>-->
        <!--<a href="map.php">Map</a>-->
        <!--<br>-->
        <!--<a href="search.php">Artist Search</a>-->
        <br>
        <a href="logout.php">Logout</a>
      </p>
    <?php  } else { ?>
      <p>
        <a href="home.php">Home</a>
        <br>
        <a href="login.php">Login</a>
        <br>
        <a href="register.php">Register</a>
        <br>
        <a href="events.php">Events</a>
        <br>
        <a href="map.php">Map</a>
        <br>
        <a href="search.php">Artist Search</a>
        <br>
        <a href="forum.php">Forum</a>
      </p>
    <?php  }  ?>
  </div>
</header>