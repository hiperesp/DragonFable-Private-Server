# DragonFable Private Server 2024 [WIP]

A private server for DragonFable, allowing custom game and server file locations.

### Patched files:

- `/src/cdn/loader/DFLoader-patched.swf`: Added ability to change the gamefiles path + server path through DFversion by flashvars (if not provided, `web/DFversion.txt` will be used)
- `/src/cdn/gamefiles/game15_8_05-patched.swf`: Match server path from `DFLoader-patched.swf`
- `/src/cdn/gamefiles/game15_9_00-patched.swf`: Match server path from `DFLoader-patched.swf`
- `/src/cdn/flash/usersignup-9Dec15-patched.swf`: Match server path like `DFLoader-patched.swf`

### Features:

- Custom `gamefiles` location (can be served by any domain or same. CORS headers may be required)
- Custom `server` location (can be hosted by another server or same. CORS headers may be required)

### Prerequisites:

- Docker
- Docker Compose

### Setup:

1. Install `docker` and `docker-compose` on your machine if you haven't already.
2. Clone this repository:
    ```sh
    git clone https://github.com/hiperesp/DragonFable-Private-Server/
    cd DragonFable-Private-Server
    ```
3. (Optional) Download the offline gamefiles from [here](https://www.mediafire.com/file/7ce4vkkwokmx2h1/gamefiles.zip/file) and extract it to `/src/cdn/gamefiles/`\
    **Note**: If you don't download the gamefiles, the server will progressively download them as each game file is requested. This means that as you play, the server will fetch the necessary files in real-time, ensuring you can continue playing without interruption.

4. (Optional) If you want to use a .env.php file, copy the /src/server-emulator/.env.default.php to /src/server-emulator/.env.php and edit the values as needed.\
    **Note**: If you don't create the .env.php file, the server will use the default system environment variables.

### Usage:

1. Start the server:
    ```sh
    docker-compose up
    ```
2. Access the game at `http://localhost:40000` in your browser.

### Demo:

https://dragon-fable.deploy.app.br/

-----

#### Dev info:

I am using `flash.external.ExternalInterface.call("console.log", "Hello ExternalInterface");` to log flash messages to browser console.

-----

## Credits
Inspired by [AlphaFable, by MentalBlank](https://github.com/MentalBlank/AlphaFable).

## Contributing:

Feel free to submit issues or pull requests. Contributions are welcome!

## License:

This project is licensed under the MIT License. See the `LICENSE` file for details.

