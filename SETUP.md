# DragonFable Private Server - Setup

## Using Docker:

### Prerequisites:

- [Docker](https://docs.docker.com/get-docker/)
- [Docker Compose](https://docs.docker.com/compose/install/)
- [Git](https://git-scm.com/downloads) (Optional)

**Note**: If you don't have Git installed, you can download the repository as a ZIP file by clicking the green "Code" button at the top of the repository page.

### Steps:

1. Clone this repository:
    ```sh
    git clone https://github.com/hiperesp/DragonFable-Private-Server/
    cd DragonFable-Private-Server
    ```

2. Configure the server:
    - Using environment variables:
        Read the /src/server-emulator/.config.default.php file and set the environment variables accordingly.
    - Using a .config.php file:
        Copy the /src/server-emulator/.config.default.php to /src/server-emulator/.config.php and edit the values as needed.

    You can use MySQL or SQLite. See `/src/server-emulator/.config.default.php` for more details.

    **Note**: If you don't create the .config.php file, the server will use the default system environment variables.

3. (Optional) Download the offline gamefiles from [here](https://www.mediafire.com/file/7ce4vkkwokmx2h1/gamefiles.zip/file) (updated at 2025-01-14) and extract it to `/src/cdn/gamefiles/`\
    **Note**: If you don't download the gamefiles, the server will progressively download them as each game file is requested **only if you use the `cache` mode at `gamefilesPath` settings**. This means that as you play, the server will fetch the necessary files in real-time, ensuring you can continue playing without interruption.

### Usage:

1. Start the server using Docker Compose:
    ```sh
    docker-compose up
    ```

2. Access the game at `http://localhost:40000` in your browser.

3. Setup the database using [upgrade tool](UPGRADE.md).

4. Now you can play the game!

## Without Docker (using shared hosting also):

### Prerequisites:

- PHP 8.4 + Apache2 in server
- MySQL or SQLite support in php
- Git (Optional)

**Note**: If you don't have Git installed, you can download the repository as a ZIP file by clicking the green "Code" button at the top of the repository page.

### Steps:

1. Clone this repository to your local machine:
    ```sh
    git clone https://github.com/hiperesp/DragonFable-Private-Server/
    ```

2. Upload the following dirs to your hosting provider:
    - `/src/cdn/` to `public_html/cdn/`
    - `/src/server-emulator/` to `public_html/server-emulator/`
    - `/src/web/` to `public_html/` (move the files to the root dir)

3. Configure the server:
    Copy the `/src/server-emulator/.config.default.php` to `/src/server-emulator/.config.php` and edit the values as needed.

    You can use MySQL or SQLite. See `/src/server-emulator/.config.default.php` for more details. By default, the server will use SQLite.

4. (Optional) Download the offline gamefiles from [here](https://www.mediafire.com/file/7ce4vkkwokmx2h1/gamefiles.zip/file) (updated at 2024-11-10) and extract it to `public_html/cdn/gamefiles/`\
    **Note**: If you don't download the gamefiles, the server will progressively download them as each game file is requested **only if you use the `cache` mode at `gamefilesPath` settings**. This means that as you play, the server will fetch the necessary files in real-time, ensuring you can continue playing without interruption.

### Usage:

1. The server should be running.

2. Access the game using your domain in your browser.

3. Setup the database using [upgrade tool](UPGRADE.md).

4. Now you can play the game!
