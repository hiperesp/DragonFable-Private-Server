# DragonFable Private Server 2024 [WIP]

### Patched files:

- /cdn/loader/DFLoader-patched.swf: Added ability to change the gamefiles path + server path through DFversion by flashvars (if not provided, web/DFversion.txt will be file)
- /cdn/gamefiles/game15_8_05-patched.swf: Match server path from DFLoader-patched.swf

### Features:

- Custom `gamefiles` location (can be served by any domain (or same). CORS headers must be required)
- Custom `server` location (can be hosted by another server or same. CORS headers must be required)
