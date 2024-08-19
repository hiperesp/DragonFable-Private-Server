# DragonFable Private Server 2024 [WIP]

### Patched files:

- /src/cdn/loader/DFLoader-patched.swf: Added ability to change the gamefiles path + server path through DFversion by flashvars (if not provided, web/DFversion.txt will be file)
- /src/cdn/gamefiles/game15_8_05-patched.swf: Match server path from DFLoader-patched.swf
- /src/cdn/flash/usersignup-9Dec15-patched.swf: Match server path like DFLoader-patched.swf

### Features:

- Custom `gamefiles` location (can be served by any domain or same. CORS headers may be required)
- Custom `server` location (can be hosted by another server or same. CORS headers may be required)

### How to run:

By default, the Dockerfile is configured to run with external cdn server. You need to change all occurrences of `https://df.hiper.esp.br` to your cdn address.

You can use self hosted cdn like `http://localhost/cdn`, just replace `https://df.hiper.esp.br` with `http://localhost/cdn` in server settings + `play.html` and `signup.html`.

### DEMO:

https://dragon-fable.deploy.app.br/

##### Ignore

Using `flash.external.ExternalInterface.call("console.log", "Hello ExternalInterface");` to log flash messages to browser console.
