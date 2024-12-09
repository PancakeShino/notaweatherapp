<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Setlist Venue Mapper</title>
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
    <h1>Show Map</h1>
    <label for="setlist-id">Enter Setlist ID:</label>
    <input type="text" id="setlist-id" placeholder="e.g., 3bd6ec2e">
    <button id="search-btn">Search</button>

    <div id="map"></div>
    <script src="map.js"></script>
</body>
</html>
