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

            try {
                const response = await fetch(serverLocation + "/api/web-stats.json");
                if(response.status !== 200) throw new Error("Invalid response status code");
                const data = await response.json();

                serverStatus.online = true;
                serverStatus.onlineUsers = Number(data.onlineUsers);
                serverStatus.status = data.status;
                serverStatus.time = data.serverTime;
                serverStatus.version = data.serverVersion;
                serverStatus.gitRev = data.gitRev;
            } catch (error) {
            }
            updateServerInfo(serverStatus);
        }
        function updateServerInfo(serverStatus) {
            function syncServerTime() {
                const serverTime = new Date(serverStatus.time);
                serverTime.setSeconds(serverTime.getSeconds() + 1);
                const serverTimeStr = serverTime.toLocaleString();

                listeners.time.forEach(function(element) {
                    element.textContent = serverTimeStr;
                });
            }
            clearInterval(serverTimeInterval);
            serverTimeInterval = setInterval(syncServerTime, 1000);
            syncServerTime();

            listeners.onlineUsers.forEach(function(element) {
                element.textContent = serverStatus.onlineUsers || 0;
                element.style.color = "hsl(60deg, 60%, 50%)";
            });

            listeners.status.forEach(function(element) {
                element.textContent = serverStatus.status?.text ? serverStatus.status.text : "Offline";
                element.style.color = serverStatus.status?.color? serverStatus.status.color: "hsl(0deg, 60%, 50%)";
            });

            listeners.version.forEach(function(element) {
                element.textContent = serverStatus.version || "Unknown";
            });

            listeners.gitRev.forEach(function(element) {
                element.textContent = serverStatus.gitRev ? serverStatus.gitRev.substring(0, 7) : "Unknown";
                element.href = serverStatus.gitRev ? "https://github.com/hiperesp/DragonFable-Private-Server/compare/"+serverStatus.gitRev.substring(0, 7)+"..php8.2" : "javascript:void(0)";
                element.target = serverStatus.gitRev ? "_blank" : null;
            });
        }

        let hasListener = false;
        for(const key in listeners) {
            if(listeners[key].length > 0) {
                hasListener = true;
                break;
            }
        }
        if(!hasListener) return;

        setInterval(checkServerInfo, 5000); // 5 seconds
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

    if(window.setupLocation) {
        (function() {
            function startSetupServer(setupServerScreen, serverLocation, setupLocation) {
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

                    disableSetupButton([
                        "Creating config file...",
                    ]);
                    const response = await fetch(setupLocation, {
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

                    enableSetupButton("Setup");
                    await alertDialog(response.message);

                    if(response.success) {
                        setupButtonEl.disabled = true;

                        disableSetupButton([
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

                        const response = await fetch(serverLocation+"/dev/database/setup", {
                            method: "POST",
                            body: ""
                        }).then(response => response.text())
                        .catch(async error => {
                            console.error(error);
                            await alertDialog("An unexpected error occurred. Please try again later.");
                        });

                        disableSetupButton([
                            "Setup completed",
                        ], false);

                        await alertDialog(response);
                    }
                });
            }
            let setupButtonTextsInterval = null;
            function enableSetupButton(text) {
                const setupButtonEl = document.querySelector("[data-id='setup-button']");
                setupButtonEl.disabled = false;
                setupButtonEl.textContent = text;
                setupButtonEl.classList.remove("spin-custom");
                if(setupButtonTextsInterval) {
                    clearInterval(setupButtonTextsInterval);
                    setupButtonTextsInterval = null;
                }
            }
            function disableSetupButton(texts, spinner = true) {
                const setupButtonEl = document.querySelector("[data-id='setup-button']");
                setupButtonEl.disabled = true;
                setupButtonEl.textContent = "";

                if(setupButtonTextsInterval) {
                    clearInterval(setupButtonTextsInterval);
                    setupButtonTextsInterval = null;
                }

                let i = 0;
                function nextText() {
                    const text = texts[i] + (spinner ? " " : "");
                    setupButtonEl.textContent = text;
                    if(spinner) {
                        const spinnerEl = document.createElement("i");
                        spinnerEl.classList.add("bi", "bi-arrow-repeat", "spin-custom");
                        setupButtonEl.appendChild(spinnerEl);
                    }
                    i = (i + 1) % texts.length;
                }
                setupButtonTextsInterval = setInterval(nextText, 3000);
                nextText();
            }

            const setupServerScreen = document.querySelector("#setup-server-container");
            if(setupServerScreen) {
                startSetupServer(setupServerScreen, window.serverLocation, window.setupLocation);
            }
        })();
    }
});