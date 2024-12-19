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

async function embedSpotifyWidget(artistName) {
    const spotifyContainer = document.getElementById('spotify-widget');
    spotifyContainer.innerHTML = `<p>Loading Spotify widget...</p>`;

    try {
        const spotifyArtist = await fetchSpotifyArtist(artistName);

        if (!spotifyArtist) {
            spotifyContainer.innerHTML = `<p>Spotify artist not found.</p>`;
            return;
        }

        const spotifyEmbedUrl = `https://open.spotify.com/embed/artist/${spotifyArtist.id}`;
        spotifyContainer.innerHTML = `
            <iframe 
                src="${spotifyEmbedUrl}" 
                width="100%" 
                height="380" 
                frameborder="0" 
                allowtransparency="true" 
                allow="encrypted-media">
            </iframe>
        `;
    } catch (error) {
        spotifyContainer.innerHTML = `<p>Error loading Spotify widget: ${error.message}</p>`;
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

        const exactMatches = artistData.artist.filter(
            artist => normalizeString(artist.name) === normalizeString(artistName)
        );

        if (exactMatches.length === 0) {
            statsContainer.innerHTML = `<p>No artists found with the exact name "${artistName}".</p>`;
            return;
        }

        suggestionsContainer.innerHTML = `<p>Found ${exactMatches.length} matches for "${artistName}":</p>`;
        const suggestionsList = exactMatches
            .map(artist => {
                const isFollowing = artist.is_following ? 'Unfollow' : 'Follow';
                return `
                    <div>
                        <p><strong>Name:</strong> ${artist.name}</p>
                        <p><strong>Description:</strong> ${artist.disambiguation || 'N/A'}</p>
                        <button onclick="fetchSetlists('${artist.mbid}', '${artist.name}')">Select</button>
                        <span id="follow-button-${artist.mbid}">
                            <?php if (isset($_SESSION['validLogin']) && $_SESSION['validLogin']) { ?>
                                <button onclick="toggleFollowArtist('${artist.mbid}', '${artist.name}')" class="btn btn-secondary">
                                    ${isFollowing}
                                </button>
                            <?php } ?>
                        </span>
                    </div>
                `;
            })
            .join('');
        suggestionsContainer.innerHTML += suggestionsList;
        statsContainer.innerHTML = '';
    } catch (error) {
        statsContainer.innerHTML = `<p>Error fetching data: ${error.message}</p>`;
    }
}

async function fetchSetlists(mbid, artistName) {
    const statsContainer = document.getElementById('stats');
    const spotifyContainer = document.getElementById('spotify-widget');
    statsContainer.innerHTML = `<p>Loading setlists...</p>`;
    spotifyContainer.innerHTML = '';

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

        await embedSpotifyWidget(artistName);
    } catch (error) {
        statsContainer.innerHTML = `<p>Error fetching setlists: ${error.message}</p>`;
    }
}

async function toggleFollowArtist(artistMbid, artistName) {
    try {
        const response = await fetch('followartist.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                artist_id: artistMbid,
                artist_name: artistName
            }),
        });
        
        try {
            const data = JSON.parse(responseBody);
            console.log('Parsed data:', data);
        } catch (error) {
            console.error('Invalid JSON response:', responseBody);
            throw new Error('Failed to parse JSON');
        }        

        if (data.success) {
            const button = document.querySelector(`#follow-button-${artistMbid} button`);
            button.textContent = data.following ? 'Unfollow' : 'Follow';
        } else {
            alert('Error: ' + data.message);
        }
    } catch (error) {
        console.error('Error:', error);
        alert('Error: ' + error.message);
    }
}