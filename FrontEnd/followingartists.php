<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Followed Artists</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <style>
        .card-img-top {
            height: 200px;
            object-fit: cover;
        }
        .spotify-embed {
            margin-top: 1rem;
            margin-bottom: 1rem;
        }
    </style>
</head>
<body>
    <?php include('header.php'); ?>

    <main class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-10"> <!-- Increased width for better display -->
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white text-center">
                        <h1 class="h4">Your Artists</h1>
                    </div>
                    <div class="card-body">
                        <div id="followed-artists-list" class="mt-4">
                            <p class="text-center">Loading your followed artists...</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Bootstrap JS -->
    <script src="js/bootstrap.bundle.min.js"></script>

    <script src="js/followingartists.js"></script>
</body>
</html>