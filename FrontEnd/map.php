<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Map</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="css/bootstrap.min.css" >
    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <style>
        #map {
            height: 500px;
            width: 100%;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <?php
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    include('header.php');
    ?>

    <main class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow-sm">
                    <div class="card-header bg-success text-white text-center">
                        <h1 class="h4">Show Map</h1>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="setlist-id" class="form-label">Enter Setlist ID:</label>
                            <input type="text" id="setlist-id" class="form-control" placeholder="e.g., 3bd6ec2e">
                        </div>
                        <div class="d-grid">
                            <button id="search-btn" class="btn btn-success">Search</button>
                        </div>
                    </div>
                </div>
                <div id="map" class="mt-4"></div>
            </div>
        </div>
    </main>

    <?php include('footer.php'); ?>
    
    <!-- Bootstrap JS -->
    <script src="js/bootstrap.bundle.min.js"></script>
    <script src="js/map.js"></script>
</body>
</html>