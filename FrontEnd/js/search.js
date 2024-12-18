const apiKey = 'pEhpqLvlgcuitpMIPpAjWU-iqFQbnVUiKKKr';
const proxyUrl = 'https://cors-anywhere.herokuapp.com/';

const spotifyClientId = '677e2f8403d2428897df946b3f7f9dea';
const spotifyClientSecret = '1fc68365d40e4970af38e9461de74664';
let spotifyAccessToken = '';

async function getSpotifyAccessToken() {
    const tokenURL = 'https://accounts.spotify.com/api/token';
    const credentials = btoa(`${spotifyClientId}:${spotifyClientSecret}`);

    const response = await fetch(tokenURL, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
            'Authorization': `Basic ${credentials}`,
        },
        body: 'grant_type=client_credentials',
    });

    const data = await response.json();
    spotifyAccessToken = data.access_token;
}

async function fetchSpotifyArtist(artistName) {
    if (!spotifyAccessToken) await getSpotifyAccessToken();

    const searchAPI = `https://api.spotify.com/v1/search?q=${encodeURIComponent(artistName)}&type=artist&limit=1`;
    const response = await fetch(searchAPI, {
        headers: {
            'Authorization': `Bearer ${spotifyAccessToken}`,
        },
    });

    const data = await response.json();
    return data.artists.items[0]; // first result
}

function normalizeString(str) {
    return str
        .toLowerCase()
        .normalize('NFD') // remplaces special characters
        .replace(/[\u0300-\u036f]/g, ''); // diacritic removal
}

async function fetchSetlists(mbid) {
    const statsContainer = document.getElementById('stats');
    statsContainer.innerHTML = `<p>Loading setlists...</p>`;

    try {
        const setlistAPI = `https://api.setlist.fm/rest/1.0/artist/${mbid}/setlists?p=1`;
        const setlistResponse = await fetch(`${proxyUrl}${setlistAPI}`, {
            headers: {
                'x-api-key': apiKey,
                'Accept': 'application/json',
            },
        });
        const setlistData = await setlistResponse.json();

        if (!setlistData.setlist || setlistData.setlist.length === 0) {
            statsContainer.innerHTML = `<p>No setlists found for the selected artist.</p>`;
            return;
        }

        const recentSetlist = setlistData.setlist[0];
        const recentVenue = recentSetlist.venue.name;
        const recentDate = recentSetlist.eventDate;

        statsContainer.innerHTML = `
            <p><strong>Most Recent Setlist:</strong></p>
            <ul>
                <li><strong>Venue:</strong> ${recentVenue}</li>
                <li><strong>Date:</strong> ${recentDate}</li>
            </ul>
        `;
    } catch (error) {
        statsContainer.innerHTML = `<p>Error fetching setlists: ${error.message}</p>`;
    }
}

async function fetchArtistStats() {
    const artistName = document.getElementById('artist-input').value.trim();
    const statsContainer = document.getElementById('stats');
    const suggestionsContainer = document.getElementById('artist-suggestions');

    if (!artistName) {
        statsContainer.innerHTML = `<p>Please enter an artist name.</p>`;
        suggestionsContainer.innerHTML = '';
        return;
    }

    statsContainer.innerHTML = `<p>Loading...</p>`;
    suggestionsContainer.innerHTML = '';

    try {
        const artistAPI = `https://api.setlist.fm/rest/1.0/search/artists?artistName=${encodeURIComponent(artistName)}&p=1`;
        const artistResponse = await fetch(`${proxyUrl}${artistAPI}`, {
            headers: {
                'x-api-key': apiKey,
                'Accept': 'application/json',
            },
        });
        const artistData = await artistResponse.json();

        if (!artistData.artist || artistData.artist.length === 0) {
            statsContainer.innerHTML = `<p>No artists found with the name "${artistName}".</p>`;
            return;
        }

        // filter artists with exact matching names
        const exactMatches = artistData.artist.filter(
            artist => normalizeString(artist.name) === normalizeString(artistName)
        );

        if (exactMatches.length === 0) {
            statsContainer.innerHTML = `<p>No artists found with the exact name "${artistName}".</p>`;
            return;
        }

        // displays exact matches
        suggestionsContainer.innerHTML = `<p>Found ${exactMatches.length} matches for "${artistName}":</p>`;
        const suggestionsList = exactMatches
            .map(artist => `
                <div>
                    <p><strong>Name:</strong> ${artist.name}</p>
                    <p><strong>Description:</strong> ${artist.disambiguation || 'N/A'}</p>
                    <button onclick="fetchSetlists('${artist.mbid}')">Select</button>
                </div>
            `)
            .join('');
        suggestionsContainer.innerHTML += suggestionsList;
        statsContainer.innerHTML = '';
    } catch (error) {
        statsContainer.innerHTML = `<p>Error fetching data: ${error.message}</p>`;
    }
}