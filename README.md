# DragonFable Private Server [WIP]

A private server for DragonFable, allowing custom game and server file locations.

This project allows you to run a private server for DragonFable with customizable game and server file locations.

## Setup

### Using Docker:

#### Prerequisites:

- [Docker](https://docs.docker.com/get-docker/)
- [Docker Compose](https://docs.docker.com/compose/install/)
- [Git](https://git-scm.com/downloads) (Optional)

**Note**: If you don't have Git installed, you can download the repository as a ZIP file by clicking the green "Code" button at the top of the repository page.

#### Steps:

1. Clone this repository:
    ```sh
    git clone https://github.com/hiperesp/DragonFable-Private-Server/
    cd DragonFable-Private-Server
    ```

2. Configure the server:
    - Using environment variables:
        Read the /src/server-emulator/.env.default.php file and set the environment variables accordingly.
    - Using a .env.php file:
        Copy the /src/server-emulator/.env.default.php to /src/server-emulator/.env.php and edit the values as needed.

    You can use MySQL or SQLite. See `/src/server-emulator/.env.default.php` for more details.

    **Note**: If you don't create the .env.php file, the server will use the default system environment variables.

3. (Optional) Download the offline gamefiles from [here](https://www.mediafire.com/file/7ce4vkkwokmx2h1/gamefiles.zip/file) and extract it to `/src/cdn/gamefiles/`\
    **Note**: If you don't download the gamefiles, the server will progressively download them as each game file is requested **only if you use the `cache` mode at `gamefilesPath` settings**. This means that as you play, the server will fetch the necessary files in real-time, ensuring you can continue playing without interruption.

#### Usage:

1. Start the server using Docker Compose:
    ```sh
    docker-compose up
    ```

2. Access the game at `http://localhost:40000` in your browser and play.

### Without Docker (or shared hosting):

#### Prerequisites:

- PHP 8.4 + Apache2 in server
- MySQL or SQLite support in php
- Git (Optional)

**Note**: If you don't have Git installed, you can download the repository as a ZIP file by clicking the green "Code" button at the top of the repository page.

#### Steps:

1. Clone this repository to your local machine:
    ```sh
    git clone https://github.com/hiperesp/DragonFable-Private-Server/
    ```

2. Upload the following dirs to your hosting provider:
    - `/src/cdn/` to `public_html/cdn/`
    - `/src/server-emulator/` to `public_html/server-emulator/`
    - `/src/web/` to `public_html/` (move the files to the root dir)

3. Configure the server:
    Copy the `/src/server-emulator/.env.default.php` to `/src/server-emulator/.env.php` and edit the values as needed.

    You can use MySQL or SQLite. See `/src/server-emulator/.env.default.php` for more details.

4. (Optional) Download the offline gamefiles from [here](https://www.mediafire.com/file/7ce4vkkwokmx2h1/gamefiles.zip/file) and extract it to `public_html/cdn/gamefiles/`\
    **Note**: If you don't download the gamefiles, the server will progressively download them as each game file is requested **only if you use the `cache` mode at `gamefilesPath` settings**. This means that as you play, the server will fetch the necessary files in real-time, ensuring you can continue playing without interruption.

#### Usage:

1. The server should be running.

2. Access the game using your domain in your browser and play.

## More Info:

See [Settings](CONFIG.md) for more information on how to configure the server.

See [Development](DEV.md) for more information on how to develop the server.

### Demo:

https://dragonfable.hiper.esp.br/

-----

## Credits

Inspired by [AlphaFable, by MentalBlank](https://github.com/MentalBlank/AlphaFable).

Artix Entertainment (Creators of DragonFable and Intellectual Property holders)

## Acknowledgments

Thank you to everyone who has starred the project! Your support is greatly appreciated.

[![Stargazers repo roster for @hiperesp/DragonFable-Private-Server](https://reporoster.com/stars/dark/hiperesp/DragonFable-Private-Server)](https://github.com/hiperesp/DragonFable-Private-Server/stargazers)

We are aiming to reach 16 stars! If you enjoy the project, consider giving it a star.

## Contributing:

Feel free to submit issues or pull requests. Contributions are welcome!

## License:

This project is licensed under the MIT License. See the `LICENSE` file for details.

