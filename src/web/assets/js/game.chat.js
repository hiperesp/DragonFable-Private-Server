window.hiperesp.dfps.addEventListener("load", function() {

    hiperesp.dfps.addEventListener("logged", function(user) {
        hiperesp.dfps.modules.chat.user = {
            id: user.UserID,
            token: user.strToken,
            name: user.customParam_username,
        };
        drawChat();
        hiperesp.dfps.modules.chat.updateHistory();
    });

    const listeners = [];
    hiperesp.dfps.modules.chat = {};
    hiperesp.dfps.modules.chat.user = null;
    hiperesp.dfps.modules.chat.char = null;
    hiperesp.dfps.modules.chat.messages = [];
    hiperesp.dfps.modules.chat.updateHistory = async function() {
        if(!hiperesp.dfps.modules.chat.serverLocation) {
            throw new Error("Chat server is not configured.");
        }
        try {
            hiperesp.dfps.modules.chat.messages = await fetch(hiperesp.dfps.modules.chat.serverLocation+"/chat", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                },
                body: JSON.stringify({
                    token: hiperesp.dfps.modules.chat.user?.token,
                }),
            }).then(response => response.json());
        } catch(e) {
            console.error(e);
            for(let message of hiperesp.dfps.modules.chat.messages) {
                if(message.id == "error") {
                    return;
                }
            }
            hiperesp.dfps.modules.chat.messages.push({
                id: "error",
                type: "system",
                pinned: true,
                message: "Can't connect to the chat server. Please try again later.",
                from: "System",
            });
        }
        drawChat();
    },
    hiperesp.dfps.modules.chat.prepareInputMessage = function(message) {
        listeners.forEach(listener => {
            if(listener.prepareInputMessage) {
                listener.prepareInputMessage(message);
            }
        });
    }
    hiperesp.dfps.modules.chat.start = async function(serverLocation, newListeners) {
        listeners.push(newListeners);
        hiperesp.dfps.modules.chat.serverLocation = serverLocation;
        hiperesp.dfps.modules.chat.updateHistory();
        setInterval(hiperesp.dfps.modules.chat.updateHistory, 1000);
    }
    hiperesp.dfps.modules.chat.sendMessage = async function(message) {
        hiperesp.dfps.modules.chat.messages = await fetch(serverLocation+"/chat", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
            },
            body: JSON.stringify({
                message: message,
                token: hiperesp.dfps.modules.chat.user.token,
            }),
        }).then(response => response.json());
        drawChat();
    }
    hiperesp.dfps.modules.chat.richText = function(text) {
        let div = document.createElement("div");
        div.textContent = text;

        let safeText = div.innerHTML;

        // enable images
        safeText = safeText.replace(/(^|\s+)\[img\](https?:\/\/[^\s]+)\[\/img\](\s+|$)/g, (match, before, url, after) => {
            return `${before}<img src="${url}" alt="Image" style="max-width: 100%;max-height: 100%" />${after}`;
        });

        // enable links
        safeText = safeText.replace(/(^|\s+)(https?:\/\/[^\s]+)(\s+|$)/g, (match, before, url, after) => {
            return `${before}<a href="${url}" target="_blank">${match}</a>${after}`;
        });

        // apply bold in commands starting with /
        safeText = safeText.replace(/(^|\s+)(\/[a-zA-Z0-9_]+(?: (?:&lt;[a-zA-Z0-9_]+&gt;|\[[a-zA-Z0-9_]+\]))*)(\s+|$)/g, (match, before, command, after) => {
            return `${before}<b style="text-decoration: underline;cursor: pointer" onclick="hiperesp.dfps.modules.chat.copyCommand(this)">${command}</b>${after}`;
        });

        // add new lines
        safeText = safeText.replace(/\r?\n/g, "<br />");

        return safeText;
    }
    hiperesp.dfps.modules.chat.copyCommand = async function(element) {
        const prompt = window.promptDialog || window.prompt;
        const command = element.textContent;
        let newCommand = "";

        let parameters = command.match(/(\/[a-zA-Z0-9_]+ ?|<[a-zA-Z0-9_]+>|\[[a-zA-Z0-9_]+\])/g);
        for(let i=0; i<parameters.length; i++) {
            const parameter = parameters[i];
            if(parameter.startsWith("/")) {
                // is not a parameter, is the command itself
                newCommand += parameter.trim();
                continue;
            }

            const isOptional = parameter.startsWith("[");
            let text = "What is the value for "+parameter+"?";
            if(isOptional) {
                text += " (Optional)";
            }

            const paramValue = await prompt(text);
            if(!paramValue) {
                break;
            }

            // lets count the spaces to skip them
            const spaces = paramValue.match(/ /g);
            if(spaces && spaces.length>0) {
                i+=spaces.length;
            }

            newCommand+= " "+paramValue;
        }

        hiperesp.dfps.modules.chat.prepareInputMessage(newCommand);
    }

    function drawChat() {
        listeners.forEach(listener => {
            if(listener.drawChat) {
                listener.drawChat();
            }
        });
    }

});