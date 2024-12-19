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
    #map-popup {
        display: none;
        position: fixed;
        bottom: 0;
        left: 0;
        width: 100%;
        background: #fff;
        z-index: 1000;
        border-top: 2px solid #ddd;
        box-shadow: 0 -2px 10px rgba(0, 0, 0, 0.1);
    }

    .event-popup {
        padding: 10px;
    }

    .event-popup-close {
        position: absolute;
        top: 10px;
        right: 20px;
        cursor: pointer;
        font-size: 1.5em;
        color: #333;
    }

    #map-popup {
        height: 300px;
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
                            <input type="text" id="artist-input" class="form-control" placeholder="e.g. Metallica or Daft Punk">
                        </div>
                        <div class="d-grid">
                            <button id="search-btn" class="btn btn-primary">Search</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- right panel: events list -->
            <div class="col-md-8">
                <div id="event-list">
                    <div class="text-center text-muted">Enter an artist name to see events.</div>
                </div>
            </div>
        </div>

        <div id="map-popup" class="event-popup" style="display: none;">
            <span class="event-popup-close" onclick="closeMapPopup()">×</span>
            <div id="map-popup"></div>
        </div>
    </main>

    <script>
    let mapInstance = null;

    document.addEventListener('click', function (event) {
        const popupContainer = document.getElementById('map-popup');

        // close when clikcing outside the map
        if (popupContainer.style.display === 'block' && !popupContainer.contains(event.target) && !event.target.classList.contains('view-map-btn')) {
            closeMapPopup();
        }
    });

    function closeMapPopup() {
        const popupContainer = document.getElementById('map-popup');
        popupContainer.style.display = 'none';

        if (mapInstance) {
            mapInstance.remove();
            mapInstance = null;
        }
    }

    function openMapPopup(lat, long, venueName, cityName) {
        const popupContainer = document.getElementById('map-popup');
        popupContainer.style.display = 'block';

        // resets the map
        const mapElement = document.getElementById('map-popup');
        mapElement.innerHTML = '';

        // kills map instance
        if (mapInstance) {
            mapInstance.remove();
        }

        // creates map
        mapInstance = L.map('map-popup', {
            center: [lat, long],
            zoom: 15,
            attributionControl: false,
        });

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
        }).addTo(mapInstance);

        L.marker([lat, long])
            .addTo(mapInstance)
            .bindPopup(`<b>${venueName}</b><br>${cityName}`)
            .openPopup();
    }
    </script>


    <?php include('footer.php');?>
    <!-- Bootstrap JS -->
    <script src="js/bootstrap.bundle.min.js"></script>

    <script src="js/events.js"></script>
</body>
</html>