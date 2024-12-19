<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Artist Search</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="css/bootstrap.min.css" >
</head>
<body>
    <?php include('header.php');?>

    <main class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white text-center">
                        <h1 class="h4">Artist Search (setlist.fm stats)</h1>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <input type="text" id="artist-input" class="form-control" placeholder="Enter artist name..." />
                        </div>
                        <div class="d-grid">
                            <button onclick="fetchArtistStats()" class="btn btn-primary">Search</button>
                        </div>
                        <div id="stats" class="mt-4 alert alert-info text-center">Enter an artist name.</div>
                        <div id="artist-suggestions" class="mt-5"></div>
                        <div id="spotify-widget" class="mt-4"></div>       
                    </div>
                </div>
            </div>
        </div>
    </main>

    <?php include('footer.php');?>

    <!-- Bootstrap JS -->
    <script src="js/bootstrap.bundle.min.js"></script>
    <script src="js/search.js"></script>
</body>
</html>