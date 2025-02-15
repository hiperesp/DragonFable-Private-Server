if(!window.hiperesp) {
    window.hiperesp = {};

    let loaded = false;

    const eventListeners = {
        load: [],
        logged: [],
    };

    window.hiperesp.dfps = {
        modules: {},
        externalInterface: {
            logged: function(user) {
                dispatch("logged", [ user ]);
            }
        },
        addEventListener: function(event, callback) {
            if(!eventListeners[event]) {
                throw new Error("Event not found: " + event);
            }
            eventListeners[event].push(callback);

            if(event=="load" && loaded) {
                callback();
            }
        },
        getUserLevel: function(user) {
            if(user.UserID == 1) {
                return "admin";
            }
            if(user.intAccessLevel == 0) {
                return "free";
            }
            if(user.intAccessLevel == 1) {
                return "special";
            }
            if(user.intAccessLevel == 2) {
                return "upgraded";
            }
            return "unknown";
        },
    };

    function dispatch(eventName, arguments = []) {
        for(let eventListener of eventListeners[eventName]) {
            eventListener.apply(null, arguments);
        }
    }

    loaded = true;
    dispatch("load");
}
