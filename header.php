<!-- Nick Malefyt. IT 490 10/26/2024 -->
<header>
<!--Setlist.fm API KEY: pEhpqLvlgcuitpMIPpAjWU-iqFQbnVUiKKKr -->    
  <div class="topnav">
<!--<img src="images/image." height="75"/>-->
  <br>

    <!-- if usr logged in.-->
    if (isset($_SESSION['validLogin'])) { 
      <p>Hello, echo $_SESSION['username']?>/</p>

      <p>
        <a href="home.php">Home</a>
        <br>
        <a href="login.php">Login</a>
        <br>
      <!-- add other pages for the website and eventually remove login from these choices.
       <a href="dashboard.php">Dashboard</a>
       <br>
       <a href="myconcerts.php">My Concerts</a>
       <br>
       <a href="tourmap.php">Tour Map</a>
       <br>
       <a href="artist.php">Artist Lookup</a>
       <br>-->
      <a href="logout.php">Logout</a>
    </p>

php } else { ?>
  <p>
    <a href="home.php">Home</a>
    <br>
    <a href="login.php">Login</a>
    <br>
    <a href= "register.html">Register</a>
    </p>
    
    php }  ?> 

    </div>
</header>