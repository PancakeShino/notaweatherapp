const apiKey = 'pEhpqLvlgcuitpMIPpAjWU-iqFQbnVUiKKKr';
const proxyUrl = 'https://cors-anywhere.herokuapp.com/';
const spotifyClientId = '677e2f8403d2428897df946b3f7f9dea';
const spotifyClientSecret = '1fc68365d40e4970af38e9461de74664';
let spotifyAccessToken = '';

// Function to get Spotify Access Token
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

// Function to fetch Spotify Artist Data
async function fetchSpotifyArtist(artistName) {
    if (!spotifyAccessToken) await getSpotifyAccessToken();

    const searchAPI = `https://api.spotify.com/v1/search?q=${encodeURIComponent(artistName)}&type=artist&limit=1`;
    const response = await fetch(searchAPI, {
        headers: {
            'Authorization': `Bearer ${spotifyAccessToken}`,
        },
    });

    const data = await response.json();
    return data.artists.items[0]; // Return the first matching artist
}

// Main Function: Fetch Artist Stats
async function fetchArtistStats() {
    const artistName = document.getElementById('artist-input').value.trim();
    const statsContainer = document.getElementById('stats');

    if (!artistName) {
        statsContainer.innerHTML = `<p>Please enter an artist name.</p>`;
        return;
    }

    statsContainer.innerHTML = `<p>Loading...</p>`;

    try {
        // Fetch artist data from setlist.fm
        const artistAPI = `https://api.setlist.fm/rest/1.0/search/artists?artistName=${encodeURIComponent(artistName)}&p=1`;
        const artistResponse = await fetch(`${proxyUrl}${artistAPI}`, {
            headers: {
                'x-api-key': apiKey,
                'Accept': 'application/json',
            },
        });
        const artistData = await artistResponse.json();

        if (!artistData.artist || artistData.artist.length === 0) {
            statsContainer.innerHTML = `<p>No artist found with the name "${artistName}".</p>`;
            return;
        }

        const artist = artistData.artist.find(a => a.name.toLowerCase() === artistName.toLowerCase());
        if (!artist) {
            statsContainer.innerHTML = `<p>No exact match found for "${artistName}".</p>`;
            return;
        }

        // Fetch all setlists
        const allSetlists = [];
        let page = 1;
        let hasMorePages = true;

        while (hasMorePages) {
            const setlistAPI = `https://api.setlist.fm/rest/1.0/artist/${artist.mbid}/setlists?p=${page}`;
            const setlistResponse = await fetch(`${proxyUrl}${setlistAPI}`, {
                headers: {
                    'x-api-key': apiKey,
                    'Accept': 'application/json',
                },
            });
            const setlistData = await setlistResponse.json();

            if (setlistData.setlist && setlistData.setlist.length > 0) {
                allSetlists.push(...setlistData.setlist);
                page++;
                if (setlistData.setlist.length < 20) {
                    hasMorePages = false;
                }
            } else {
                hasMorePages = false;
            }
        }

        if (allSetlists.length === 0) {
            statsContainer.innerHTML = `<p>No setlists found for "${artist.name}".</p>`;
            return;
        }

        // Calculate stats
        const totalSetlists = allSetlists.length;
        const recentSetlist = allSetlists[0];
        const recentVenue = recentSetlist.venue.name;
        const recentDate = recentSetlist.eventDate;

        const [day, month, year] = recentDate.split('-');
        const parsedDate = new Date(year, month - 1, day);
        const options = { year: 'numeric', month: 'long', day: 'numeric' };
        const formattedDate = parsedDate.toLocaleDateString('en-US', options);

        // Fetch Spotify data
        const spotifyArtist = await fetchSpotifyArtist(artistName);
        let spotifyEmbed = '';
        if (spotifyArtist) {
            spotifyEmbed = `
                <iframe
                    src="https://open.spotify.com/embed/artist/${spotifyArtist.id}"
                    width="20%" height="500"
                    frameborder="0"
                    allowtransparency="true"
                    allow="encrypted-media">
                </iframe>
            `;
        } else {
            spotifyEmbed = `<p>Spotify data not available for "${artistName}".</p>`;
        }

        // Update statsContainer with results
        statsContainer.innerHTML = `
            <p><strong>Artist Name:</strong> ${artist.name}</p>
            <p><strong>Total Setlists:</strong> ${totalSetlists}</p>
            <p><strong>Most Recent Setlist:</strong></p>
            <ul>
                <li><strong>Venue:</strong> ${recentVenue}</li>
                <li><strong>Date:</strong> ${formattedDate}</li>
            </ul>
            <p><strong>Listen on Spotify:</strong></p>
            ${spotifyEmbed}
        `;

    } catch (error) {
        statsContainer.innerHTML = `<p>Error fetching data: ${error.message}</p>`;
    }
}

/*const apiKey = 'pEhpqLvlgcuitpMIPpAjWU-iqFQbnVUiKKKr';
const proxyUrl = 'https://cors-anywhere.herokuapp.com/';

async function fetchArtistStats() {
    const artistName = document.getElementById('artist-input').value.trim();
    const statsContainer = document.getElementById('stats');
    
    if (!artistName) {
        statsContainer.innerHTML = `<p>Please enter an artist name.</p>`;
        return;
    }
    
    statsContainer.innerHTML = `<p>Loading...</p>`;
    
    try {
        // Fetch information
        const artistAPI = `https://api.setlist.fm/rest/1.0/search/artists?artistName=${encodeURIComponent(artistName)}&p=1`;
        const artistResponse = await fetch(`${proxyUrl}${artistAPI}`, {
            headers: {
                'x-api-key': apiKey,
                'Accept': 'application/json',
            },
        });
        const artistData = await artistResponse.json();

        // Check if artist exists
        if (!artistData.artist || artistData.artist.length === 0) {
            statsContainer.innerHTML = `<p>No artist found with the name "${artistName}".</p>`;
            return;
        }

        // Search for exact match (& gather MusicBrainz ID)
        const artist = artistData.artist.find(a => a.name.toLowerCase() === artistName.toLowerCase());
        if (!artist) {
            statsContainer.innerHTML = `<p>No exact match found for "${artistName}".</p>`;
            return;
        }

        // Fetch all setlists
        const allSetlists = [];
        let page = 1;
        let hasMorePages = true;

        while (hasMorePages) {
            const setlistAPI = `https://api.setlist.fm/rest/1.0/artist/${artist.mbid}/setlists?p=${page}`;
            const setlistResponse = await fetch(`${proxyUrl}${setlistAPI}`, {
                headers: {
                    'x-api-key': apiKey,
                    'Accept': 'application/json',
                },
            });
            const setlistData = await setlistResponse.json();

            if (setlistData.setlist && setlistData.setlist.length > 0) {
                allSetlists.push(...setlistData.setlist);
                page++;
                if (setlistData.setlist.length < 20) {
                    hasMorePages = false;
                }
            } else {
                hasMorePages = false;
            }
        }

        if (allSetlists.length === 0) {
            statsContainer.innerHTML = `<p>No setlists found for "${artist.name}".</p>`;
            return;
        }

        // Calculate stats
        const totalSetlists = allSetlists.length;
        const recentSetlist = allSetlists[0]; // Get the most recent setlist
        const recentVenue = recentSetlist.venue.name;
        const recentDate = recentSetlist.eventDate;

        // Make readable date
        const [day, month, year] = recentDate.split('-'); // split date
        const parsedDate = new Date(year, month - 1, day); // date object (month is 0-indexed)
        const options = {year: 'numeric', month: 'long', day: 'numeric'};
        const formattedDate = parsedDate.toLocaleDateString('en-US', options); // format date

        statsContainer.innerHTML = `
            <p><strong>Artist Name:</strong> ${artist.name}</p>
            <p><strong>Total Setlists:</strong> ${totalSetlists}</p>
            <p><strong>Most Recent Setlist:</strong></p>
            <ul>
                <li><strong>Venue:</strong> ${recentVenue}</li>
                <li><strong>Date:</strong> ${formattedDate}</li>
            </ul>
        `;

    } catch (error) {
        statsContainer.innerHTML = `<p>Error fetching data: ${error.message}</p>`;
    }
}*/
