window.hiperesp.dfps.addEventListener("load", function() {
    hiperesp.dfps.modules.Chat = class Chat {
        static instances = [];

        constructor(serverLocation) {
            this.serverLocation = serverLocation;
            this.listeners = {
                prepareInputMessage: [],
                render: [],
                loggedIn: [],
            };
            this.user = null;
            this.char = null;
            this.messages = [];
            this.eventSource = null;
            this.instance = Chat.instances.length;
            Chat.instances.push(this);

            hiperesp.dfps.addEventListener("logged", user => {
                this.user = {
                    id: user.UserID,
                    token: user.strToken,
                    name: user.customParam_username,
                };
                this.reset();
            });
        }

        reset() {
            if(!this.serverLocation) {
                throw new Error("Chat server is not configured.");
            }
            if(this.eventSource) {
                this.eventSource.close();
                this.messages = [];
            }
            this.eventSource = new EventSource(this.serverLocation+"/chat/stream?" + new URLSearchParams({
                token: this.user?.token || "",
            }));

            this.eventSource.onmessage = event => {
                if(this.messages.length === 0) {
                    this.render(); // to reset the chat if user logged in now
                }
                const messages = JSON.parse(event.data);
                messages.removed.forEach(id => {
                    const index = this.messages.findIndex(message => message.id === id);
                    if(index>=0) {
                        this.messages.splice(index, 1);
                    }
                });
                messages.new.forEach(message => {
                    this.messages.push(message);
                });
                this.render();
            };

            this.eventSource.onerror = event => {
                this.messages.push({
                    id: "error",
                    type: "system",
                    pinned: true,
                    message: "Can't connect to the chat server. Please try again later.",
                    from: "System",
                });
                if(this.eventSource) {
                    this.eventSource.close();
                }
                this.render();
            };
        }

        prepareInputMessage(message) {
            this.listeners.prepareInputMessage.forEach(listener => {
                listener(message);
            });
        }

        render() {
            this.listeners.render.forEach(listener => {
                listener();
            });
        }

        sendMessage(message) {
            fetch(this.serverLocation+"/chat/send-message", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                },
                body: JSON.stringify({
                    message: message,
                    token: this.user.token,
                }),
            }).then(response => response.text())
            .then(data => {
                this.render();
            });
        }

        static richText(text, instance = null) {
            let div = document.createElement("div");
            div.textContent = text;

            let safeText = div.innerHTML;

            // enable images
            safeText = safeText.replace(/(^|\s+)\[img\](https?:\/\/[^\s]+)\[\/img\](\s+|$)/g, (match, before, url, after) => {
                return `${before}<img src="${url}" alt="Image" style="max-width: 100%;max-height: 100%" />${after}`;
            });

            // enable links
            safeText = safeText.replace(/(^|\s+)(https?:\/\/[^\s]+)(\s+|$)/g, (match, before, url, after) => {
                return `${before}<a href="${url}" target="_blank">${url}</a>${after}`;
            });

            // apply bold in commands starting with /
            safeText = safeText.replace(/(^|\s+)(\/[a-zA-Z0-9_]+(?: (?:&lt;[a-zA-Z0-9_]+&gt;|\[[a-zA-Z0-9_]+\]))*)(\s+|$)/g, (match, before, command, after) => {
                let onClick = "";
                if(instance !== null) {
                    onClick = `onclick="hiperesp.dfps.modules.Chat.instances[${instance}].copyCommand(this)"`;
                }
                return `${before}<b style="text-decoration: underline;cursor: pointer" ${onClick}>${command}</b>${after}`;
            });

            // add new lines
            safeText = safeText.replace(/\r?\n/g, "<br />");

            return safeText;
        }

        copyCommand = async function(element) {
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

            this.prepareInputMessage(newCommand);
        }

        addEventListener(event, listener) {
            if(!this.listeners[event]) {
                throw new Error("Event "+event+" is not supported.");
            }
            this.listeners[event].push(listener);
        }

        start() {
            this.reset();
        }
    }

});