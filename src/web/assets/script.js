/** RUFFLE PLAYER */
window.RufflePlayer.config = {
    splashScreen: false,
    autoplay: "on",
    unmuteOverlay: "hidden",
    muted: true
};

/** FULLSCREEN */
(function() {
    const element = document.querySelector("#game-container-fullscreen");
    if(!element) return;
    const button = document.querySelector("#btn-toggle-fullscreen");
    if(!button) return;

    button.addEventListener("click", function() {
        if (document.fullscreenElement) {
            document.exitFullscreen();
            button.innerHTML = "Enter<br>Fullscreen";
        } else {
            element.requestFullscreen();
            button.innerHTML = "Exit<br>Fullscreen";
        }
    });
})();

/**  SERVER STATUS */
(function() {
    function checkServerInfo() {
        document.querySelectorAll("[data-server-stats-provider]").forEach(async function(element) {
            const url = element.dataset.serverStatsProvider;

            let isServerAvailable;
            let countOnlinePlayers;
            let serverVersion;
            try {
                const response = await fetch(url);
                if(response.status !== 200) throw new Error("Invalid response status code");
                const data = await response.json();
                isServerAvailable = true;
                countOnlinePlayers = Number(data.onlineUsers);
                serverVersion = data.serverVersion;
            } catch (error) {
                console.log(error);

                isServerAvailable = false;
                countOnlinePlayers = 0;
                serverVersion = "Unknown";
            }

            const serverStatusEl = element.querySelector("[data-server-id='server-status']");
            const serverPlayersOnlineEl = element.querySelector("[data-server-id='server-players-online']");
            const serverVersionEl = element.querySelector("[data-server-id='server-version']");

            if(serverStatusEl) serverStatusEl.textContent = isServerAvailable ? "Online" : "Offline";
            if(serverPlayersOnlineEl) serverPlayersOnlineEl.textContent = countOnlinePlayers;
            if(serverVersionEl) serverVersionEl.textContent = serverVersion;
        });
    }
    setInterval(checkServerInfo, 60000); // 1 minute
    checkServerInfo();
})();
