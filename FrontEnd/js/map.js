const apiKey = "pEhpqLvlgcuitpMIPpAjWU-iqFQbnVUiKKKr";
const proxyUrl = "https://cors-anywhere.herokuapp.com/";
const baseUrl = "https://api.setlist.fm/rest/1.0";

const mapElement = document.getElementById("map");
let mapInstance = null;

document.getElementById("search-btn").addEventListener("click", () => {
    const setlistId = document.getElementById("setlist-id").value.trim();
    if (!setlistId) {
        alert("Please enter a setlist ID.");
        return;
    }

    fetchSetlistData(setlistId);
});

async function fetchSetlistData(setlistId) {
    try {
        const setlistAPI = `${baseUrl}/setlist/${setlistId}`;
        const response = await fetch(`${proxyUrl}${setlistAPI}`, {
            headers: {
                "x-api-key": apiKey,
                "Accept": "application/json",
            },
        });

        if (!response.ok) {
            throw new Error("Setlist not found or an error occurred.");
        }

        const setlist = await response.json();
        const venue = setlist.venue;
        const location = venue.city;
        const {lat, long} = location.coords;

        displayMap(lat, long, venue.name, location.name, venue.url);
    } catch (error) {
        alert(error.message);
    }
}

function displayMap(lat, long, venueName, cityName, venueUrl) {
    if (mapInstance) {
        mapInstance.remove();
    }

    mapInstance = L.map("map").setView([lat, long], 13);

    L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
        maxZoom: 19,
    }).addTo(mapInstance);

    L.marker([lat, long])
        .addTo(mapInstance)
        .bindPopup(`<b>${venueName}</b><br>${cityName}<br><a href="${venueUrl}" target="_blank">More Info</a>`)
        .openPopup();
}