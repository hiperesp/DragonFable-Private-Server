
# DragonFable Private Server - Dev info

There are some tools and tips that can help you to develop the server.

## Tools:

You can se the `/dev-tools/` dir for some tools that can help you to develop the server.

### extract-production-data

Is a helper to extract the production data from the database, using only in the development.

Tools:
- `extract-production-data.php`: Extract the production data and store in `xml` files.
- `convert-production-data-to-json.php`: Convert the `xml` files to `json` files, in order to be used by the server.
- `remove-duplicated-mergeShops-items.php`: Remove the duplicated items from the `mergeShops` items. Some items can be duplicated in the `mergeShops` items, so this tool will remove the duplicated items.

Other tools:
- `extract-swf-from-extracted-production-data.php`: Extract the `swf` files from the `xml` files from `extract-production-data.php` tool and request it to the local cdn server at cache mode. (untested after some changes). If some `swf` file is not found, it will be stored in `current-swf-download-fails.txt` file.

### extract-swf-strings

This tool will extract the `swf strings` from the `swf` files. The idea is search more `swf` which are not in the `extract-production-data.php`.

### patch-new-swf

This tool will patch the new `game*.swf` files. The idea is when a new release is out at the original game, you can patch it to work with the server. See the last `patch-new-swf/game*-info.txt` to see the changes that was made and compare it with the new `game*-info.txt` to see if something is broken.

### references

This folder has some files that can help to understand some logic from the game. Currently it has only `alphafable.sql`, this file has some tables from the 2011 private server version. Some structures can be useful.

## FAQ

### Why you patched some files?

This is the patched files. You can't use the original files from the game, as some things are hardcoded to work only with the original server.

- `/src/cdn/loader/DFLoader-patched.swf`: Added ability to change the gamefiles path + server path through DFversion by flashvars (if not provided, `web/DFversion.txt` will be used)
- `/src/cdn/gamefiles/game*-patched.swf`: Some bug fixes + Improvements + Match server path from `DFLoader-patched.swf`
- `/src/cdn/flash/usersignup-9Dec15-patched.swf`: Match server path like `DFLoader-patched.swf` does.

### How to debug the game.swf?

I don't known anything about flash, but I was able to debug the game.swf file using the [FFDec](https://www.free-decompiler.com/flash/download/) tool.

I am using `flash.external.ExternalInterface.call("console.log", "Hello ExternalInterface");` to log flash messages to browser console, so I can debug the game.

### Why you stored the `interfaces` files in the `/src/cdn/gamefiles` folder?

Some files are updated by the server, and the new updated file name is changed everytime and the old file is removed from production server. If our interfaces table be outdated, the game will try to download a invalid file from production. See `/src/cdn/gamefiles/interfaces/_ql/` and `/src/cdn/gamefiles/interfaces/banners/` dirs.

So, I stored the `interfaces` files in the `/src/cdn/gamefiles` folder to avoid this problem. The server will always return the correct file, and if a update is needed, I manually download the new swf file, store and update the `interfaces` table.

## Automated tests

I don't know much about automated tests, but I am using a `phpt` file to test some functions.

To test, we can go to `http://localhost:40000/server-emulator/server.php/dev/` and select the test to run.

To develop a test, copy some `phpt` file from the `/src/server-emulator/hiperesp/tests/*/` dir and create a new one. It looks like the official `phpt` files from PHP, but with some custom changes.