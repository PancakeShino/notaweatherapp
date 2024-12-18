<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Artist Event Finder</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <style>
        #map {
            height: 500px;
            width: 100%;
        }
    </style>
</head>
<body>
    <?php include('header.php');?>

    <main class="container-fluid mt-4">
        <div class="row">
            <!-- left panel: search -->
            <div class="col-md-4">
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-primary text-white">
                        <h1 class="h5">Find Events</h1>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="artist-input" class="form-label">Enter Artist Name</label>
                            <input type="text" id="artist-input" class="form-control" placeholder="e.g., Metallica">
                        </div>
                        <div class="d-grid">
                            <button id="search-btn" class="btn btn-primary">Search</button>
                        </div>
                    </div>
                </div>
                <div id="event-list" class="list-group">
                    <div class="text-center text-muted">Enter an artist name to see events.</div>
                </div>
            </div>

            <!-- right panel: map -->
            <div class="col-md-8">
                <div id="map" class="border rounded shadow-sm"></div>
            </div>
        </div>
    </main>

    <!-- Bootstrap JS -->
    <script src="js/bootstrap.bundle.min.js"></script>
    <script src="js/events.js"></script>
</body>
</html>
