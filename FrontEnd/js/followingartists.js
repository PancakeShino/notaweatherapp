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

async function getArtistID(artistName) {
    try {
        if (!spotifyAccessToken) await getSpotifyAccessToken();

        const response = await fetch(`https://api.spotify.com/v1/search?q=${encodeURIComponent(artistName)}&type=artist&limit=1`, {
            headers: {
                'Authorization': `Bearer ${spotifyAccessToken}`,
            }
        });

        const data = await response.json();
        
        if (data && data.artists && data.artists.items && data.artists.items.length > 0) {
            return data.artists.items[0].id;
        }
        console.log('No Spotify results found for:', artistName);
        return null;
    } catch (error) {
        console.error('Error in getArtistID:', error);
        return null;
    }
}

async function getRelatedArtists(spotifyArtistId) {
    try {
        if (!spotifyArtistId) return [];
        if (!spotifyAccessToken) await getSpotifyAccessToken();

        const response = await fetch(`https://api.spotify.com/v1/artists/${spotifyArtistId}/related-artists`, {
            headers: {
                'Authorization': `Bearer ${spotifyAccessToken}`,
            }
        });

        const data = await response.json();
        
        if (data && data.artists && Array.isArray(data.artists)) {
            return data.artists.slice(0, 3); // return top 3 related artists
        }
        return [];
    } catch (error) {
        console.error('Error in getRelatedArtists:', error);
        return [];
    }
}

async function fetchArtistDetails(artistId) {
    try {
        const proxyUrl = 'https://cors-anywhere.herokuapp.com/';
        const apiKey = 'pEhpqLvlgcuitpMIPpAjWU-iqFQbnVUiKKKr';

        const response = await fetch(`${proxyUrl}https://api.setlist.fm/rest/1.0/artist/${artistId}`, {
            headers: {
                'x-api-key': apiKey,
                'Accept': 'application/json'
            }
        });

        if (!response.ok) {
            console.error('Setlist.fm API error:', response.status);

            const spotifyArtistId = await getArtistID(artistId);
            if (spotifyArtistId) {
                const relatedArtists = await getRelatedArtists(spotifyArtistId);
                return {
                    name: artistId,
                    disambiguation: '',
                    relatedArtists,
                    spotifyArtistId
                };
            }
            throw new Error(`Failed to fetch artist details: ${response.status}`);
        }

        const artistData = await response.json();
        
        // spotify reccomendations
        try {
            const spotifyArtistId = await getArtistID(artistData.name);
            let relatedArtists = [];
            if (spotifyArtistId) {
                relatedArtists = await getRelatedArtists(spotifyArtistId);
            }
            return { ...artistData, relatedArtists, spotifyArtistId };
        } catch (spotifyError) {
            console.error('Spotify API error:', spotifyError);
            // return artist data if Spotify fails
            return { ...artistData, relatedArtists: [], spotifyArtistId: null };
        }
    } catch (error) {
        console.error('Error in fetchArtistDetails:', error);
        throw error;
    }
}

async function fetchFollowedArtists() {
    const container = document.getElementById('followed-artists-list');
    
    try {
        const response = await fetch('getartists.php');
        const data = await response.json();
        
        if (!data.success) {
            container.innerHTML = `<p class="text-center text-danger">${data.message}</p>`;
            return;
        }

        if (!data.artists || data.artists.length === 0) {
            container.innerHTML = `<p class="text-center">You haven't followed any artists yet.</p>`;
            return;
        }

        const artistsList = await Promise.all(data.artists.map(async (artistId) => {
            try {
                const artistData = await fetchArtistDetails(artistId);
                const spotifyEmbed = artistData.spotifyArtistId ? 
                    `<iframe src="https://open.spotify.com/embed/artist/${artistData.spotifyArtistId}"  
                        width="100%" height="380" frameborder="0" allowtransparency="true" 
                        allow="encrypted-media"></iframe>` : '';

                const relatedArtistsHtml = artistData.relatedArtists && artistData.relatedArtists.length > 0 ?
                    `<div class="mt-3">
                        <h6>Similar Artists:</h6>
                        <div class="row">
                            ${artistData.relatedArtists.map(related => `
                                <div class="col-md-4">
                                    <div class="card mb-2">
                                        <img src="${related.images?.[0]?.url || ''}" class="card-img-top" alt="${related.name}">
                                        <div class="card-body">
                                            <h6 class="card-title">${related.name}</h6>
                                            <a href="https://open.spotify.com/artist/${related.id}" 
                                               target="_blank" 
                                               class="btn btn-sm btn-success">
                                                Listen on Spotify
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            `).join('')}
                        </div>
                    </div>` : '';

                return `
                    <div class="card mb-4">
                        <div class="card-body">
                            <h5 class="card-title">${artistData.name}</h5>
                            <p class="card-text">${artistData.disambiguation || 'No additional information available'}</p>
                            <button onclick="toggleFollowArtist('${artistId}', '${artistData.name}')" class="btn btn-danger mb-3">
                                Unfollow
                            </button>
                            ${spotifyEmbed}
                            ${relatedArtistsHtml}
                        </div>
                    </div>
                `;
            } catch (error) {
                console.error('Error processing artist:', artistId, error);
                return `
                    <div class="card mb-3">
                        <div class="card-body">
                            <h5 class="card-title">Artist ID: ${artistId}</h5>
                            <p class="text-danger">Unable to load complete artist information. The artist may no longer be available.</p>
                            <button onclick="toggleFollowArtist('${artistId}', '')" class="btn btn-danger mb-3">
                                Unfollow
                            </button>
                        </div>
                    </div>
                `;
            }
        }));

        container.innerHTML = artistsList.join('');
    } catch (error) {
        console.error('Main error:', error);
        container.innerHTML = `<p class="text-center text-danger">Error loading followed artists: ${error.message}</p>`;
    }
}

async function toggleFollowArtist(artistId, artistName) {
    try {
        const response = await fetch('followartist.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                artist_id: artistId,
                artist_name: artistName
            })
        });
        
        const data = await response.json();
        if (data.success) {
            fetchFollowedArtists();
        } else {
            alert('Error: ' + data.message);
        }
    } catch (error) {
        alert('Error: ' + error.message);
    }
}

document.addEventListener('DOMContentLoaded', fetchFollowedArtists);