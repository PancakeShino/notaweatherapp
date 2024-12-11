<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Setlist Venue Mapper</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
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
    <?php include('header.php');?>

    <main class="container mt-5">
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

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="map.js"></script>
</body>
</html>