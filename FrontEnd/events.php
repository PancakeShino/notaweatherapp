<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Artist Event Finder</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
        }
        .container {
            display: flex;
            flex: 1;
            height: 100vh;
        }
        .left-panel {
            flex: 1;
            padding: 20px;
            overflow-y: auto;
            box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1);
        }
        .right-panel {
            flex: 2;
            position: relative;
        }
        #map {
            height: 100%;
            width: 100%;
        }
        .event {
            margin-bottom: 20px;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            background: #f9f9f9;
        }
        .event p {
            margin: 5px 0;
        }
        button {
            padding: 8px 12px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Left Panel: Search and Event List -->
        <div class="left-panel">
            <h1>Find Upcoming Events</h1>
            <input type="text" id="artist-input" placeholder="Enter artist name" style="width: 100%; padding: 10px; margin-bottom: 10px;" />
            <button id="search-btn">Search</button>
            <div id="event-list" style="margin-top: 20px;"></div>
        </div>

        <!-- Right Panel: Map -->
        <div class="right-panel">
            <div id="map"></div>
        </div>
    </div>

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="events.js"></script>
</body>
</html>
