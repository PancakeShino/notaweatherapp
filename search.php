<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>setlist.fm API - Artist Search</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
        }
        /*.container {
            max-width: 600px;
            margin: 20px auto;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 8px;
        } */
        input [type="text"] {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        button {
            padding: 5px 10px;
            background-color: #007BFF;
            color: #fff;
            border: none;
            border-radius: 6px;
            cursor: pointer;
        }
        button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Artist Search - setlist.fm Stats</h1>
        <input type="text" id="artist-input" placeholder="Enter artist name..." />
        <button onclick="fetchArtistStats()">Search</button>
        <div id="stats">Enter an artist name.</div>
        <div id="artist-suggestions"></div>
    </div>
    <script src="search.js"></script>
</body>
</html>
