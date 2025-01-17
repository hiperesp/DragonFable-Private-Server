
# DragonFable Private Server - Dev info

There are some tools and tips that can help you to develop the server.

## Tools:

You can se the `/dev-tools/` dir for some tools that can help you to develop the server.

To use some tools, you need to enter in container and run the command. Example:
```sh
docker exec -it dragonfable-web /bin/bash # Enter in container, you must be at /var/www/html/ dir
cd dev-tools/ # Enter in dev-tools dir
cd TOOL_NAME/ # Enter in the tool dir (replace TOOL_NAME with the tool dir name, you can see the tools list below or use `ls` command)
php TOOL_NAME.php # Run the tool (replace TOOL_NAME with the tool name, you can see the tools list below or use `ls` command)
```

### download-production-data

Is a new helper to extract the production data from the database, using only in the development.

Tools:
- `download.php`: Will download all known production data from the server and store in `xml` files, in format: `downloaded/{$type}/{$id}.xml`.
- `convert.php`: Convert the `xml` files to `json` files, in order to be used by the server.
- `download-swf.php`: Download all `swf` files from the server based on the converted `json` files. It will store in the `cdn` folder.

To update, run in order:
```sh
php download.php
php convert.php
php download-swf.php
```

### patch-new-swf

This tool will patch the new `game*.swf` files. The idea is when a new release is out at the original game, you can patch it to work with the server. See the last `patch-new-swf/game*-info.txt` to see the changes that was made and compare it with the new `game*-info.txt` to see if something is broken.

## FAQ

### Why you patched some files?

This is the patched files. You can't use the original files from the game, as some things are hardcoded to work only with the original server.

- `/src/cdn/loader/DFLoader-patched.swf`: Added ability to change the gamefiles path + server path through DFversion by flashvars (if not provided, `web/DFversion.txt` will be used)
- `/src/cdn/gamefiles/game*-patched.swf`: Some bug fixes + Improvements + Match server path from `DFLoader-patched.swf`
- `/src/cdn/flash/usersignup-9Dec15-patched.swf`: Match server path like `DFLoader-patched.swf` does.

### How to debug the swf files?

I don't known anything about flash, but I was able to debug the game.swf file using the [FFDec](https://github.com/jindrapetrik/jpexs-decompiler/releases) tool.

I am using `flash.external.ExternalInterface.call("console.log", "Hello ExternalInterface");` to log flash messages to browser console, so I can debug the game.
