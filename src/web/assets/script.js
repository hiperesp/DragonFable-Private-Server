/** RUFFLE PLAYER */
window.RufflePlayer.config = {
    splashScreen: false,
    autoplay: "on",
    unmuteOverlay: "hidden",
    muted: true
};

/**  SERVER STATUS */
(function() {
    function checkServerInfo() {
        document.querySelectorAll("[data-server-stats-provider]").forEach(async function(element) {
            const url = element.dataset.serverStatsProvider;

            let isServerAvailable;
            let countOnlinePlayers;
            let serverTime;
            let serverVersion;
            let gitRev;
            try {
                const response = await fetch(url);
                if(response.status !== 200) throw new Error("Invalid response status code");
                const data = await response.json();
                isServerAvailable = true;
                countOnlinePlayers = Number(data.onlineUsers);
                serverTime = data.serverTime;
                serverVersion = data.serverVersion;
                gitRev = data.gitRev;
            } catch (error) {
                console.log(error);

                isServerAvailable = false;
                countOnlinePlayers = 0;
                serverTime = "Unknown";
                serverVersion = "Unknown";
                gitRev = null;
            }

            const serverStatusEl = element.querySelector("[data-server-id='server-status']");
            const serverPlayersOnlineEl = element.querySelector("[data-server-id='server-players-online']");
            const serverTimeEl = element.querySelector("[data-server-id='server-time']");
            const serverVersionEl = element.querySelector("[data-server-id='server-version']");
            const gitRevEl = document.querySelector("#git-rev");

            if(serverStatusEl) serverStatusEl.textContent = isServerAvailable ? "Online" : "Offline";
            if(serverPlayersOnlineEl) serverPlayersOnlineEl.textContent = countOnlinePlayers;
            if(serverTimeEl) {
                serverTimeEl.dataset.serverTime = serverTime;
                function syncServerTime() {
                    const serverTime = new Date(serverTimeEl.dataset.serverTime);
                    serverTime.setSeconds(serverTime.getSeconds() + 1);
                    serverTimeEl.textContent = serverTime.toLocaleString();
                    serverTimeEl.dataset.serverTime = serverTime;
                }
                clearInterval(serverTimeEl.dataset.interval);
                serverTimeEl.dataset.interval = setInterval(syncServerTime, 1000);
                syncServerTime();
            }
            if(serverVersionEl) serverVersionEl.textContent = serverVersion;
            if(gitRevEl) {
                if(gitRev) {
                    gitRevEl.href = "https://github.com/hiperesp/DragonFable-Private-Server/commit/"+gitRev;
                    gitRevEl.textContent = "Current Commit: "+gitRev.substring(0, 7);
                } else {
                    gitRevEl.href = "";
                    gitRevEl.textContent = "";
                }
            }
        });
    }
    setInterval(checkServerInfo, 5000); // 5 seconds
    checkServerInfo();
})();

(function() {
    function startLostPassword(dfVersionProvider) {
        function setTabVisible(step, theCase = null) {
            let tabElement = null;
            document.querySelectorAll("[data-type='lost-password-tab']").forEach(function(element) {
                let visible = false;
                if(element.dataset.step == step) {
                    if(theCase) {
                        if(element.dataset.case == theCase) {
                            visible = true;
                        }
                    } else {
                        visible = true;
                    }
                }
                if(visible) {
                    element.style.display = "block";
                    tabElement = element;
                } else {
                    element.style.display = "none";
                }
            });
            return tabElement;
        }
        function step1() {
            const tab = setTabVisible(1);
            const emailInput = tab.querySelector("[data-id='email-field']");
            const errorText = tab.querySelector("[data-id='error-text']");
            const nextStepButton = tab.querySelector("[data-id='recover-password-button']");

            nextStepButton.addEventListener("click", function() {
                step2case1();
            });
        }
        function step2case1() {
            const tab = setTabVisible(2, 1);
            const codeInput = tab.querySelector("[data-id='code-field']");
            const errorText = tab.querySelector("[data-id='error-text']");
            const nextStepButton = tab.querySelector("[data-id='submit-code-button']");

            nextStepButton.addEventListener("click", function() {
                step3();
            });
        }
        function step2case2() {
            const tab = setTabVisible(2, 2);
            const siteEmailElements = tab.querySelectorAll("[data-id='site-email']");

            siteEmailElements.forEach(function(element) {
                const email = "support@dragonfable.hiper.esp.br";
                element.textContent = email;
                element.href = "mailto:"+email;
            });
        }
        function step3() {
            const tab = setTabVisible(3);
            const newPasswordInput = tab.querySelector("[data-id='new-password-field']");
            const confirmPasswordInput = tab.querySelector("[data-id='confirm-password-field']");
            const errorText = tab.querySelector("[data-id='error-text']");
            const nextStepButton = tab.querySelector("[data-id='submit-password-button']");

            nextStepButton.addEventListener("click", function() {
                step4();
            });
        }
        function step4() {
            setTabVisible(4);
        }

        step1();
    }
    const lostPasswordScreen = document.querySelector("#lost-password-container");
    if(lostPasswordScreen) {
        startLostPassword(lostPasswordScreen.dataset.dfVersionProvider);
    }
})();