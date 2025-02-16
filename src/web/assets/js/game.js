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
    };

    function dispatch(eventName, arguments = []) {
        for(let eventListener of eventListeners[eventName]) {
            eventListener.apply(null, arguments);
        }
    }

    loaded = true;
    dispatch("load");
}
