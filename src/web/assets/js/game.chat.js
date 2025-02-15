window.hiperesp.dfps.addEventListener("load", function() {

    hiperesp.dfps.addEventListener("logged", function(user) {
        hiperesp.dfps.modules.chat.user = {
            id: user.UserID,
            token: user.strToken,
            level: hiperesp.dfps.getUserLevel(user),
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
            hiperesp.dfps.modules.chat.messages.push({
                type: "system",
                pinned: true,
                message: "Can't connect to the chat server. Please try again later.",
                from: "System",
            });
        }
        drawChat();
    },
    hiperesp.dfps.modules.chat.start = async function(serverLocation, newListeners) {
        listeners.push(newListeners);
        hiperesp.dfps.modules.chat.serverLocation = serverLocation;
        hiperesp.dfps.modules.chat.updateHistory();
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

    hiperesp.dfps.modules.chat.updateHistory();
    setInterval(hiperesp.dfps.modules.chat.updateHistory, 1000);

    function drawChat() {
        listeners.forEach(listener => {
            if(listener.drawChat) {
                listener.drawChat();
            }
        });
    }

});