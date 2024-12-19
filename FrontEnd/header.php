<header class="d-flex flex-wrap align-items-center justify-content-center justify-content-md-between py-3 mb-4 border-bottom">
      <div class="col-md-3 mb-2 mb-md-0">
        
      <a href="home.php" class="d-inline-flex link-body-emphasis text-decoration-none">
          <img src="images/encorehub.webp" alt="encorehub logo" width="128px">
        </a>
      </div>

    <?php 
    session_start();
    if (isset($_SESSION['validLogin']) && $_SESSION['validLogin'] && isset($_SESSION['session_id'])) { 
    ?>
      <!-- <p>Hello, <?php echo htmlspecialchars($_SESSION['username']); ?>!</p> -->
      <ul class="nav col-12 col-md-auto mb-2 justify-content-center mb-md-0">
        <li><a href="home.php" class="nav-link px-2 link-secondary">Home</a></li>
        <li><a href="search.php" class="nav-link px-2">Search</a></li>
        <li><a href="followingartists.php" class="nav-link px-2">Following</a></li>
        <li><a href="events.php" class="nav-link px-2">Event Search</a></li>
        <li><a href="secret.php" class="nav-link px-2">Secret</a></li>
        <li><a href="about.php" class="nav-link px-2">About Us</a></li>
      </ul>
      <div class="col-md-3">   
        <button type="button" value="Logout" class="btn btn-primary" id="btnlogout"
        onClick="document.location.href='logout.php'">Logout</button>
      </div>

    <?php } else { ?>

      <ul class="nav col-12 col-md-auto mb-2 justify-content-center mb-md-0">
        <li><a href="home.php" class="nav-link px-2 link-secondary">Home</a></li>
        <li><a href="search.php" class="nav-link px-2">Artist Search</a></li>
        <li><a href="events.php" class="nav-link px-2">Event Search</a></li>
        
        <li><a href="about.php" class="nav-link px-2">About Us</a></li>
      </ul>

      <div class="col-md-3">

        <button type="button" value="Login" class="btn btn-primary" id="btnlogin" 
        onClick="document.location.href='login.php'">Login</button>

        <button type="button" value="Register" class="btn btn-outline-primary me-2" id="btnRegister" 
        onClick="document.location.href='register.php'">Register</button>
      </div>

    <?php  } ?>
    ?>

  </div>
</header>
