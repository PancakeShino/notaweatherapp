const apiKey = 'pEhpqLvlgcuitpMIPpAjWU-iqFQbnVUiKKKr';
const proxyUrl = 'https://cors-anywhere.herokuapp.com/';
const setlistBaseUrl = 'https://api.setlist.fm/rest/1.0';
const ticketmasterAPI = '9H7vRCdxa8GagYxTmeV6FuAeFjLuJGXY';
const ticketmasterURL = 'https://app.ticketmaster.com/discovery/v2';

async function fetchArtistEvents() {
    const artistName = document.getElementById('artist-input').value.trim();
    const eventListContainer = document.getElementById('event-list');

    if (!artistName) {
        eventListContainer.innerHTML = `<p>Please enter an artist name.</p>`;
        return;
    }

    eventListContainer.innerHTML = `<p>Loading...</p>`;

    try {
        const upcomingEventsHtml = await fetchUpcomingEvents(artistName);
        const pastEventsHtml = await fetchPastEvents(artistName);

        eventListContainer.innerHTML = `
            <div>
                <h3>Upcoming Events</h3>
                ${upcomingEventsHtml || '<p>No upcoming events found.</p>'}
            </div>
            <hr>
            <div>
                <h3>Past Events</h3>
                ${pastEventsHtml || '<p>No past events found.</p>'}
            </div>
        `;

        // Add event listeners for "View on Map" buttons
        const mapButtons = document.querySelectorAll('.view-map-btn');
        mapButtons.forEach(button => {
            button.addEventListener('click', function () {
                const lat = parseFloat(this.getAttribute('data-lat'));
                const long = parseFloat(this.getAttribute('data-long'));
                const venueName = this.getAttribute('data-venue');
                const cityName = this.getAttribute('data-city');

                if (!isNaN(lat) && !isNaN(long)) {
                    openMapPopup(lat, long, venueName, cityName);
                } else {
                    alert('Location data is missing or invalid.');
                }
            });
        });
    } catch (error) {
        eventListContainer.innerHTML = `<p>Error fetching data: ${error.message}</p>`;
        console.error(error);
    }
}

async function fetchUpcomingEvents(artistName) {
    try {
        const url = `${ticketmasterURL}/events.json?keyword=${encodeURIComponent(artistName)}&apikey=${ticketmasterAPI}`;
        const response = await fetch(url);

        if (!response.ok) {
            throw new Error(`Failed to fetch upcoming events: ${response.statusText}`);
        }

        const data = await response.json();

        if (!data._embedded || !data._embedded.events || data._embedded.events.length === 0) {
            return `<p>No upcoming events found for "${artistName}".</p>`;
        }

        return data._embedded.events
            .map(event => {
                const venue = event._embedded.venues[0];
                const eventDate = new Date(event.dates.start.dateTime || event.dates.start.localDate);
                const country = venue.country ? venue.country.name : 'Unknown Country';
                const state = venue.state && venue.state.name && country === 'United States of America' ? venue.state.name : '';

                return `
                    <div class="event">
                        <strong>${event.name}</strong><br>
                        ${venue.name} - ${venue.city.name}${state ? `, ${state}` : ''}, ${country}<br>
                        <i>${eventDate.toLocaleDateString('en-US', { month: 'long', day: 'numeric', year: 'numeric' })}</i><br>
                        <a href="${event.url}" target="_blank" class="btn btn-sm btn-primary">Buy Tickets Now</a><br><br>
                    </div>
                `;
            })
            .join('');
    } catch (error) {
        console.error(error);
        return `<p>Error fetching upcoming events: ${error.message}</p>`;
    }
}

async function fetchPastEvents(artistName) {
    try {
        const artistAPI = `${setlistBaseUrl}/search/artists?artistName=${encodeURIComponent(artistName)}&p=1`;
        const artistResponse = await fetch(`${proxyUrl}${artistAPI}`, {
            headers: {
                'x-api-key': apiKey,
                'Accept': 'application/json',
            },
        });

        if (!artistResponse.ok) {
            throw new Error(`Failed to fetch artist data: ${artistResponse.statusText}`);
        }

        const artistData = await artistResponse.json();
        if (!artistData.artist || artistData.artist.length === 0) {
            return `<p>No artist found with the name "${artistName}".</p>`;
        }

        const artist = artistData.artist.find(a => a.name.toLowerCase() === artistName.toLowerCase());
        if (!artist) {
            return `<p>No exact match found for "${artistName}".</p>`;
        }

        const eventsAPI = `${setlistBaseUrl}/artist/${artist.mbid}/setlists`;
        const eventsResponse = await fetch(`${proxyUrl}${eventsAPI}`, {
            headers: {
                'x-api-key': apiKey,
                'Accept': 'application/json',
            },
        });

        if (!eventsResponse.ok) {
            throw new Error(`Failed to fetch past events: ${eventsResponse.statusText}`);
        }

        const eventsData = await eventsResponse.json();
        if (!eventsData.setlist || eventsData.setlist.length === 0) {
            return `<p>No past events found for "${artist.name}".</p>`;
        }

        return eventsData.setlist
            .map(event => {
                const venue = event.venue;
                const city = venue.city;
                const date = new Date(event.eventDate.split('-').reverse().join('-'));

                const formattedDate = new Intl.DateTimeFormat('en-US', {
                    month: 'long',
                    day: 'numeric',
                    year: 'numeric',
                }).format(date);

                return `
                    <div class="event">
                        <strong>${venue.name}</strong><br>
                        ${city.name}${city.state ? `, ${city.state}` : ''}<br>
                        <i>${formattedDate}</i><br>
                        <button class="view-map-btn btn btn-sm btn-primary"
                            data-lat="${city.coords.lat}"
                            data-long="${city.coords.long}"
                            data-venue="${venue.name}"
                            data-city="${city.name}">View on Map</button>
                    </div><br>
                `;
            })
            .join('');
    } catch (error) {
        console.error(error);
        return `<p>Error fetching past events: ${error.message}</p>`;
    }
}

document.getElementById('search-btn').addEventListener('click', fetchArtistEvents);