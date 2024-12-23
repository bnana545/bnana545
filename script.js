async function fetchServerStatus() {
    const ip = document.getElementById('server-ip').value;
    const port = document.getElementById('server-port').value;
    const response = await fetch(`server-status.php?ip=${ip}&port=${port}`);


    if (!ip || !port) {
        alert('Please enter both IP and Port.');
        return;
    }

    try {
        const response = await fetch(`/server-status?ip=${ip}&port=${port}`);
        const data = await response.json();

        if (data.error) {
            alert(data.error);
            return;
        }

        document.getElementById('server-name').innerText = data.name;
        document.getElementById('online-players').innerText = data.onlinePlayers;
        document.getElementById('max-players').innerText = data.maxPlayers;

        const progressBarFill = document.getElementById('progress-bar-fill');
        progressBarFill.style.width = `${(data.onlinePlayers / data.maxPlayers) * 100}%`;

        const playerList = document.getElementById('player-list');
        playerList.innerHTML = '';
        data.players.forEach(player => {
            const li = document.createElement('li');
            li.innerText = player;
            playerList.appendChild(li);
        });

        document.getElementById('server-info').style.display = 'block';
    } catch (error) {
        alert('Failed to fetch server status.');
        console.error(error);
    }
}
