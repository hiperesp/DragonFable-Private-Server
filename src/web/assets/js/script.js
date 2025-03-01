(function() {
    /** RUFFLE PLAYER */
    if(window.RufflePlayer) {
        window.RufflePlayer.config = {
            splashScreen: false,
            autoplay: "on",
            unmuteOverlay: "hidden",
            muted: true
        };
    }
})();

window.addEventListener("load", function() {
    /**  SERVER STATUS */
    (function() {
        let serverTimeInterval = null;
        const listeners = {
            onlineUsers: document.querySelectorAll("[data-id='server-playing']"),
            status: document.querySelectorAll("[data-id='server-status']"),
            time: document.querySelectorAll("[data-id='server-time']"),
            version: document.querySelectorAll("[data-id='server-version']"),
            gitRev: document.querySelectorAll("[data-id='server-git-rev']"),
        };
        let serverTimeOffset = 0;
        const serverLocation = window.serverLocation;

        async function checkServerInfo() {
            const serverStatus = {
                onlineUsers: null,
                online: null,
                status: null,
                time: null,
                version: null,
                gitRev: null,
            };

            const eventSource = new EventSource(serverLocation+"/api/web-stats/stream");

            eventSource.onmessage = event => {
                const data = JSON.parse(event.data);
                serverStatus.online = true;
                serverStatus.onlineUsers = Number(data.onlineUsers);
                serverStatus.status = data.status;
                serverStatus.time = data.serverTime;
                serverStatus.version = data.serverVersion;
                serverStatus.gitRev = data.gitRev;

                updateServerInfo(serverStatus);
            };

            eventSource.onerror = async event => {
                eventSource.close();

                serverStatus.onlineUsers = null;
                serverStatus.online = false;
                serverStatus.status = null;
                serverStatus.time = null;
                serverStatus.version = null;
                serverStatus.gitRev = null;

                updateServerInfo(serverStatus);

                await new Promise(resolve => setTimeout(resolve, 1000));

                checkServerInfo();
            };
        }
        function verifyRedirect(serverStatus) {
            switch(serverStatus.status?.special) {
                case "SETUP":
                case "UPGRADE":
                    window.location.href = "setup.html";
                    break;
                case "MAINTENANCE":
                    if(window.maintenance) return; // Avoid redirect loop
                    window.location.href = "maintenance.html#back-to="+encodeURIComponent(window.location.pathname+window.location.search);
                    break;
                case "ERROR":
                    if(window.updateServerInfo_error) return; // Avoid alert spam
                    window.updateServerInfo_error = serverStatus;
                    alertDialog("An unexpected error occurred. Please contact the administrator. See the console for more details.", "Error");
                    console.error("See `window.updateServerInfo_error` for more details", serverStatus);
                    break;
                case "DONE":
                    if(window.maintenance) {
                        let backTo = "index.html";
                        if(window.location.hash) {
                            const hashQueryParams = new URLSearchParams(window.location.hash.substring(1)); // Remove the `#` then parse the query string
                            if(hashQueryParams.has("back-to")) {
                                backTo = decodeURIComponent(window.location.hash.replace("#back-to=", ""));
                            }
                        }
                        window.location.href = backTo;
                    }
                    break;
                default:
                    if(window.updateServerInfo_offline) return; // Avoid alert spam
                    window.updateServerInfo_offline = serverStatus;
                    console.error("See `window.updateServerInfo_offline` for more details", serverStatus);
                    break;
            }
        }
        function updateServerInfo(serverStatus) {
            function syncServerTime(resetOffset = false) {
                if(resetOffset) {
                    serverTimeOffset = Date.now() - new Date(serverStatus.time).getTime();
                } else {
                    serverTimeOffset += 1000;
                }
                const serverTime = new Date(serverStatus.time);
                serverTime.setSeconds(serverTime.getSeconds() + serverTimeOffset / 1000);
                const serverTimeStr = serverTime.toLocaleString();

                listeners.time.forEach(function(element) {
                    element.textContent = serverTimeStr;
                });
            }
            clearInterval(serverTimeInterval);
            serverTimeInterval = setInterval(syncServerTime, 1000);
            syncServerTime(true);

            listeners.onlineUsers.forEach(function(element) {
                element.textContent = serverStatus.onlineUsers || 0;
                element.style.color = "hsl(60deg, 60%, 50%)";
            });

            listeners.status.forEach(function(element) {
                const alertDialog = window.alertDialog || window.alert;

                element.textContent = serverStatus.status?.text ? serverStatus.status.text : "Offline";
                element.style.color = serverStatus.status?.color? serverStatus.status.color: "hsl(0deg, 60%, 50%)";
            });

            listeners.version.forEach(function(element) {
                element.textContent = serverStatus.version || "Unknown";
            });

            listeners.gitRev.forEach(function(element) {
                element.textContent = serverStatus.gitRev ? serverStatus.gitRev.substring(0, 7) : "Unknown";
                element.href = serverStatus.gitRev ? "https://github.com/hiperesp/DragonFable-Private-Server/compare/"+serverStatus.gitRev.substring(0, 7)+"..main" : "javascript:void(0)";
                element.target = serverStatus.gitRev ? "_blank" : null;
            });

            verifyRedirect(serverStatus);
        }

        let hasListener = false;
        for(const key in listeners) {
            if(listeners[key].length > 0) {
                hasListener = true;
                break;
            }
        }
        if(!hasListener) return;

        checkServerInfo();
    })();

    (function() {
        function startLostPassword(lostPasswordScreen, serverLocation) {
            function setTabVisible(step, theCase = null) {
                let tabElement = null;
                lostPasswordScreen.querySelectorAll("[data-type='lost-password-tab']").forEach(function(element) {
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
            async function request(step, data, successCallback, errorText) {
                return await fetch(serverLocation+"/api/recovery-password/"+step, {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json"
                    },
                    body: JSON.stringify(data)
                }).then(response => response.json())
                .then(data => {
                    if(data.success) {
                        successCallback();
                        return;
                    }
                    if(data.isEmailDisabled) {
                        emailDisabled(data.supportEmail);
                        return;
                    }
                    errorText.textContent = data.error;
                }).catch(error => {
                    errorText.textContent = "An unexpected error occurred. Please try again later.";
                    console.error(error);
                });
            }
            function emailDisabled(supportEmail) {
                const tab = setTabVisible(2, 2);
                const siteEmailElements = tab.querySelectorAll("[data-id='site-email']");

                siteEmailElements.forEach(function(element) {
                    element.textContent = supportEmail;
                    element.href = "mailto:"+supportEmail;
                });
            }
            const dataState = {};
            function step1() {
                const tab = setTabVisible(1);
                const emailInput = tab.querySelector("[data-id='email-field']");
                const errorText = tab.querySelector("[data-id='error-text']");
                const nextStepButton = tab.querySelector("[data-id='recover-password-button']");

                nextStepButton.onclick = async function() {
                    const email = emailInput.value;
                    if(email.length < 3) {
                        errorText.textContent = "Invalid email";
                        return;
                    }
                    dataState.email = email;

                    nextStepButton.disabled = true;
                    await request(1, dataState, step2, errorText);
                    nextStepButton.disabled = false;
                };
            }
            function step2() {
                const tab = setTabVisible(2, 1);
                const codeInput = tab.querySelector("[data-id='code-field']");
                const errorText = tab.querySelector("[data-id='error-text']");
                const nextStepButton = tab.querySelector("[data-id='submit-code-button']");

                nextStepButton.onclick = async function() {
                    const code = codeInput.value;
                    if(code.length < 1) {
                        errorText.textContent = "Invalid code";
                        return;
                    }
                    dataState.code = code;

                    nextStepButton.disabled = true;
                    await request(2, dataState, step3, errorText);
                    nextStepButton.disabled = false;
                };
            }
            function step3() {
                const tab = setTabVisible(3);
                const newPasswordInput = tab.querySelector("[data-id='new-password-field']");
                const confirmPasswordInput = tab.querySelector("[data-id='confirm-password-field']");
                const errorText = tab.querySelector("[data-id='error-text']");
                const nextStepButton = tab.querySelector("[data-id='submit-password-button']");

                nextStepButton.onclick = async function() {
                    const newPassword = newPasswordInput.value;
                    const confirmPassword = confirmPasswordInput.value;
                    if(newPassword.length < 6) {
                        errorText.textContent = "Password must have at least 6 characters";
                        return;
                    }
                    if(newPassword != confirmPassword) {
                        errorText.textContent = "Passwords do not match";
                        return;
                    }
                    dataState.password = newPassword;

                    nextStepButton.disabled = true;
                    await request(3, dataState, step4, errorText);
                    nextStepButton.disabled = false;
                };
            }
            function step4() {
                setTabVisible(4);
            }

            step1();
        }
        const lostPasswordScreen = document.querySelector("#lost-password-container");
        if(lostPasswordScreen) {
            startLostPassword(lostPasswordScreen, window.serverLocation);
        }
    })();

    (function() {
        function startManageAccount(manageAccountScreen, serverLocation) {
            function setTabVisible(step, theCase = null) {
                let tabElement = null;
                manageAccountScreen.querySelectorAll("[data-type='manage-account-tab']").forEach(function(element) {
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
            async function request(action, data, successCallback, errorText) {
                return await fetch(serverLocation+"/api/manage-account/"+action, {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json"
                    },
                    body: JSON.stringify(data)
                }).then(response => response.json())
                .then(data => {
                    if(data.success) {
                        successCallback();
                        return;
                    }
                    errorText.textContent = data.error;
                }).catch(error => {
                    errorText.textContent = "An unexpected error occurred. Please try again later.";
                    console.error(error);
                });
            }
            const dataState = {};
            function loginScreen() {
                const tab = setTabVisible("login");
                const loginInput = tab.querySelector("[data-id='login-field']");
                const passwordInput = tab.querySelector("[data-id='password-field']");
                const errorText = tab.querySelector("[data-id='error-text']");
                const doLoginButton = tab.querySelector("[data-id='do-login-button']");

                errorText.textContent = "Coming soon... If you have issues with your password, please use the Lost Password feature, by clicking on the link below.";
                window.location.href = "lost-password.html";
                return;

                doLoginButton.onclick = async function() {
                    const login = loginInput.value;
                    if(login.length < 3) {
                        errorText.textContent = "Invalid login";
                        return;
                    }
                    dataState.login = login;

                    const password = passwordInput.value;
                    if(password.length < 6) {
                        errorText.textContent = "Invalid password";
                        return;
                    }
                    dataState.password = password;

                    doLoginButton.disabled = true;
                    await request("login", dataState, dashboard, errorText);
                    doLoginButton.disabled = false;
                };
            }
            function dashboard() {

            }

            loginScreen();
        }
        const manageAccountScreen = document.querySelector("#manage-account-container");
        if(manageAccountScreen) {
            startManageAccount(manageAccountScreen, window.serverLocation);
        }
    })();

    (function() {
        async function startSetupServer(setupServerScreen, serverLocation) {
            function getServerStatus() {
                return fetch(serverLocation+"/api/web-stats.json")
                    .then(response => response.json());
            }
            function getDefaults() {
                return fetch(serverLocation+"/setup/defaults")
                    .then(response => response.json());
            }
            function setTab(step) {
                document.querySelectorAll("[data-type='setup-menu']").forEach(function(element) {
                    if(element.dataset.menu==step) {
                        element.classList.add("active");
                    } else {
                        element.classList.remove("active");
                    }
                });
                document.querySelectorAll("[data-type='setup-tab']").forEach(function(element) {
                    if(element.dataset.tab==step) {
                        element.style.display = "block";
                    } else {
                        element.style.display = "none";
                    }
                });
            }
            await getDefaults().then(data => {
                for (const [key, value] of Object.entries(data)) {
                    setupServerScreen.querySelector(key).value = value;
                }
            });
            setTab("loading");
            getServerStatus().then(data => {
                if(data.status.special == "SETUP") {
                    setTab("setup");
                } else if(data.status.special == "UPGRADE") {
                    setTab("upgrade");
                } else if(data.status.special == "MAINTENANCE") {
                    setTab("loading");
                    setTimeout(function() {
                        window.location.reload();
                    }, 5000);
                    return;
                } else {
                    setTab("play");
                }
            });

            const dbDriverEl = setupServerScreen.querySelector("[name='DB_DRIVER']");
            const setupButtonEl = setupServerScreen.querySelector("[data-id='setup-button']");
            let dbOptions = {};

            dbDriverEl.addEventListener("change", function() {
                const driver = dbDriverEl.value;
                dbOptions = {};

                setupServerScreen.querySelectorAll("[data-if-driver]").forEach(function(element) {
                    const ifDriver = element.dataset.ifDriver;

                    if(ifDriver == driver) {
                        element.style.display = "block";
                        element.querySelectorAll("[name]").forEach(function(input) {
                            dbOptions[input.name] = input;
                        });
                    } else {
                        element.style.display = "none";
                    }
                });
            });
            dbDriverEl.dispatchEvent(new Event("change"));

            setupButtonEl.addEventListener("click", async function() {
                const data = {
                    DB_DRIVER: dbDriverEl.value,
                    DB_OPTIONS: {},
                };
                for(const key in dbOptions) {
                    const input = dbOptions[key];
                    data.DB_OPTIONS[key] = input.value;
                }

                disableButton(setupButtonEl, [
                    "Creating config file...",
                ]);
                const response = await fetch(serverLocation+"/setup/create-config", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json"
                    },
                    body: JSON.stringify(data)
                }).then(response => response.json())
                .catch(error => {
                    console.error(error);
                    return { success: false, message: "An unexpected error occurred. Please try again later." };
                });

                await alertDialog(response.message);

                if(response.success) {
                    window.location.reload();
                    return;
                }
                enableButton(setupButtonEl, "Setup");
            });

            const upgradeButtonEl = setupServerScreen.querySelector("[data-id='upgrade-button']");
            upgradeButtonEl.addEventListener("click", async function() {
                disableButton(upgradeButtonEl, [
                    "Initializing database structures...",
                    "Casting data into the tables...",
                    "Syncing inventory data...",
                    "Filling up the item categories...",
                    "Aligning quest details...",
                    "Injecting user data into the system...",
                    "Clearing old logs... for a fresh start!",
                    "Populating the shop with rare treasures...",
                    "Loading the character profiles...",
                    "Building the database foundations...",
                    "Filling the world with monsters...",
                    "Patching up the merge shops...",
                    "Loading race and class data...",
                    "Configuring the houses for new adventurers...",
                    "Pushing item data into the market...",
                    "Warming up the quest system...",
                    "Loading shop inventories...",
                    "Filling the hair shops with new styles...",
                    "Binding items to characters...",
                    "Setting up the monster spawn points...",
                    "Filling quest logs with new tasks...",
                    "Establishing house shop parameters...",
                    "Deploying character and item linkages...",
                    "Synchronizing shop-item relations...",
                    "Inserting house inventory data...",
                    "Updating the merge recipes...",
                    "Seeding the character data...",
                    "Populating the system with item details...",
                    "Activating quest-monster relationships...",
                    "Sealing the database with new entries...",
                ]);

                const response = await fetch(serverLocation+"/setup/upgrade-database", {
                    method: "POST",
                    body: ""
                }).then(response => response.json())
                .catch(error => {
                    console.error(error);
                    return { success: false, message: "An unexpected error occurred. Please try again later." };
                });

                await alertDialog(response.message);

                if(response.success) {
                    window.location.reload();
                    return;
                }
                enableButton(upgradeButtonEl, "Upgrade");

            });

            const playButtonEl = document.querySelector("[data-id='play-button']");
            playButtonEl.addEventListener("click", function() {
                window.location.href = "index.html";
            });
        }
        let setupButtonTextsInterval = null;
        function enableButton(buttonEl, text) {
            buttonEl.disabled = false;
            buttonEl.textContent = text;
            buttonEl.classList.remove("spin-custom");
            if(setupButtonTextsInterval) {
                clearInterval(setupButtonTextsInterval);
                setupButtonTextsInterval = null;
            }
        }
        function disableButton(buttonEl, texts, spinner = true) {
            buttonEl.disabled = true;
            buttonEl.textContent = "";

            if(setupButtonTextsInterval) {
                clearInterval(setupButtonTextsInterval);
                setupButtonTextsInterval = null;
            }

            let i = 0;
            function nextText() {
                const text = texts[i] + (spinner ? " " : "");
                buttonEl.textContent = text;
                if(spinner) {
                    const spinnerEl = document.createElement("i");
                    spinnerEl.classList.add("bi", "bi-arrow-repeat", "spin-custom");
                    buttonEl.appendChild(spinnerEl);
                }
                i = (i + 1) % texts.length;
            }
            setupButtonTextsInterval = setInterval(nextText, 3000);
            nextText();
        }

        const setupServerScreen = document.querySelector("#setup-server-container");
        if(setupServerScreen) {
            startSetupServer(setupServerScreen, window.serverLocation);
        }
    })();
    (function() {
        function startChat(chatContainer, serverLocation) {
            const chatMessagesContainer = chatContainer.querySelector("[data-id='chat-content']");
            const chatInputContainer = chatContainer.querySelector("[data-id='chat-input']");
            const chat = new hiperesp.dfps.modules.Chat(serverLocation);
            chat.addEventListener("prepareInputMessage", function(message) {
                const input = chatInputContainer.querySelector("input");
                if(!input) {
                    return;
                }
                input.value = message;
                input.focus();
            });
            chat.addEventListener("render", function() {
                const currentUser = chat.user;
                const messages = chat.messages;

                chatMessagesContainer.querySelectorAll(".chat-item").forEach(function(element) {
                    const id = element.dataset.id;
                    if(!messages.find(message => message.id == id)) {
                        element.remove();
                    }
                });

                for(const message of messages) {
                    if(chatMessagesContainer.querySelector(`.chat-item[data-id="${message.id}"]`)) {
                        continue;
                    }

                    const containerEl = document.createElement("div");
                    containerEl.classList.add("chat-item");
                    containerEl.dataset.id = message.id;

                    const userEl = document.createElement("div");
                    userEl.classList.add("chat-user");
                    userEl.textContent = message.from.username;
                    containerEl.appendChild(userEl);

                    const messageEl = document.createElement("div");
                    messageEl.classList.add("chat-message");
                    if(message.type == "system" || message.pinned || message.from.isAdmin) {
                        messageEl.innerHTML = hiperesp.dfps.modules.Chat.richText(message.message, chat.instance);
                    } else {
                        messageEl.textContent = message.message;
                    }
                    containerEl.appendChild(messageEl);

                    const timeEl = document.createElement("div");
                    timeEl.classList.add("chat-time");
                    timeEl.textContent = new Date(message.time * 1000).toLocaleString();
                    containerEl.appendChild(timeEl);

                    if(message.type == "system") {
                        containerEl.classList.add("chat-item-system");
                        userEl.remove();
                        timeEl.remove();
                    } else if(message.type == "user") {
                        if(currentUser && message.from.id == currentUser.id) {
                            containerEl.classList.add("chat-item-me");
                        }
                    }
                    if(message.pinned) {
                        containerEl.classList.add("chat-item-pinned");
                    }

                    chatMessagesContainer.appendChild(containerEl);
                }

                let redrawChatInput = true;
                if(currentUser && chatInputContainer.querySelector(".chat-item-available")) {
                    redrawChatInput = false;
                } else if(!currentUser && chatInputContainer.querySelector(".chat-item-unlogged")) {
                    redrawChatInput = false;
                }

                if(redrawChatInput) {

                    const chatInputContainerEl = document.createElement("div");
                    chatInputContainerEl.classList.add("chat-item", "chat-item-me");

                    const chatInputUserEl = document.createElement("div");
                    chatInputUserEl.classList.add("chat-user");
                    chatInputContainerEl.appendChild(chatInputUserEl);

                    if(currentUser) {
                        chatInputContainerEl.classList.add("chat-item-available");
                        chatInputUserEl.textContent = currentUser.name;

                        const chatInputEl = document.createElement("input");
                        chatInputEl.classList.add("chat-message-input");
                        chatInputEl.placeholder = "Write your message here";
                        chatInputContainerEl.appendChild(chatInputEl);

                        chatInputEl.addEventListener("keypress", function(event) {
                            if(event.key == "Enter") {
                                const message = chatInputEl.value;
                                if(message.length < 1) return;
                                chat.sendMessage(message);
                                chatInputEl.value = "";
                            }
                        });
                    } else {
                        chatInputContainerEl.classList.add("chat-item-unlogged");
                        chatInputUserEl.textContent = "You";

                        const chatInputEl = document.createElement("div");
                        chatInputEl.classList.add("chat-message");
                        chatInputEl.textContent = "Please log in to chat with other players";
                        chatInputContainerEl.appendChild(chatInputEl);
                    }

                    while(chatInputContainer.firstChild) {
                        chatInputContainer.removeChild(chatInputContainer.firstChild);
                    }
                    chatInputContainer.appendChild(chatInputContainerEl);
                }
            });

            chat.start();
        }

        const chat = document.querySelector("#chat-container");
        if(chat) {
            startChat(chat, window.serverLocation);
        }
    })();
});