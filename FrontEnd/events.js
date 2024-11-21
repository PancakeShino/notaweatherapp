const apiKey = 'pEhpqLvlgcuitpMIPpAjWU-iqFQbnVUiKKKr';
const proxyUrl = 'https://cors-anywhere.herokuapp.com/';
const baseUrl = 'https://api.setlist.fm/rest/1.0';
let mapInstance = null; // Keep track of the map instance

// Function to fetch artist's upcoming events
async function fetchArtistEvents() {
    const artistName = document.getElementById('artist-input').value.trim();
    const eventListContainer = document.getElementById('event-list');

    if (!artistName) {
        eventListContainer.innerHTML = `<p>Please enter an artist name.</p>`;
        return;
    }

    eventListContainer.innerHTML = `<p>Loading...</p>`;

    try {
        // Fetch artist data
        const artistAPI = `${baseUrl}/search/artists?artistName=${encodeURIComponent(artistName)}&p=1`;
        const artistResponse = await fetch(`${proxyUrl}${artistAPI}`, {
            headers: {
                'x-api-key': apiKey,
                'Accept': 'application/json',
            },
        });
        const artistData = await artistResponse.json();

        if (!artistData.artist || artistData.artist.length === 0) {
            eventListContainer.innerHTML = `<p>No artist found with the name "${artistName}".</p>`;
            return;
        }

        const artist = artistData.artist.find(a => a.name.toLowerCase() === artistName.toLowerCase());
        if (!artist) {
            eventListContainer.innerHTML = `<p>No exact match found for "${artistName}".</p>`;
            return;
        }

        // Fetch upcoming events
        const eventsAPI = `${baseUrl}/artist/${artist.mbid}/events`;
        const eventsResponse = await fetch(`${proxyUrl}${eventsAPI}`, {
            headers: {
                'x-api-key': apiKey,
                'Accept': 'application/json',
            },
        });
        const eventsData = await eventsResponse.json();

        if (!eventsData || !eventsData.event || eventsData.event.length === 0) {
            eventListContainer.innerHTML = `<p>No upcoming events found for "${artist.name}".</p>`;
            return;
        }

        // Display events
        const eventsHtml = eventsData.event.map(event => `
            <div class="event" data-lat="${event.venue.city.coords.lat}" data-long="${event.venue.city.coords.long}" data-venue="${event.venue.name}" data-city="${event.venue.city.name}">
                <p><strong>${event.venue.name}</strong> - ${event.venue.city.name}</p>
                <p>${event.date}</p>
                <button class="view-map-btn">View on Map</button>
            </div>
        `).join('');
        eventListContainer.innerHTML = eventsHtml;

        // Add event listener to buttons
        document.querySelectorAll('.view-map-btn').forEach(button => {
            button.addEventListener('click', function () {
                const parent = this.parentElement;
                const lat = parent.dataset.lat;
                const long = parent.dataset.long;
                const venue = parent.dataset.venue;
                const city = parent.dataset.city;

                displayMap(lat, long, venue, city);
            });
        });
    } catch (error) {
        eventListContainer.innerHTML = `<p>Error fetching data: ${error.message}</p>`;
    }
}

// Function to display the map with venue location
function displayMap(lat, long, venueName, cityName) {
    // Clear existing map
    if (mapInstance) {
        mapInstance.remove();
    }

    // Initialize new map
    mapInstance = L.map('map').setView([lat, long], 13);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
    }).addTo(mapInstance);

    L.marker([lat, long])
        .addTo(mapInstance)
        .bindPopup(`<b>${venueName}</b><br>${cityName}`)
        .openPopup();
}

// Event listener for the search button
document.getElementById('search-btn').addEventListener('click', fetchArtistEvents);
