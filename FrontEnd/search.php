<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>setlist.fm API - Artist Search</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <?php include('header.php');?>

    <main class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white text-center">
                        <h1 class="h4">Artist Search - setlist.fm Stats</h1>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <input type="text" id="artist-input" class="form-control" placeholder="Enter artist name..." />
                        </div>
                        <div class="d-grid">
                            <button onclick="fetchArtistStats()" class="btn btn-primary">Search</button>
                        </div>
                        <div id="stats" class="mt-4 alert alert-info text-center">Enter an artist name.</div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="search.js"></script>
</body>
</html>