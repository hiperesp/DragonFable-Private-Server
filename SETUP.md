# DragonFable Private Server - Setup

## Fastest way to setup the server (windows only):

### Prerequisites:

- [XAMPP 8.2](https://www.apachefriends.org/download.html)
- [DragonFable Private Server Files (PHP 8.2)](https://github.com/hiperesp/DragonFable-Private-Server/archive/refs/heads/php8.2.zip)
- **Optional**: [Offline Gamefiles](https://www.mediafire.com/file/7ce4vkkwokmx2h1/gamefiles.zip/file) (updated at 2025-04-05)

### 1. Prepare files:

1. Download and install XAMPP 8.2.

2. Go to the XAMPP installation directory (usually `C:\xampp`) and open the `htdocs` folder.

3. Delete everything in the `htdocs` folder.

4. Extract the DragonFable Private Server Files (PHP 8.2) and go to the `src` folder.

5. Move the `cdn` and `server-emulator` folders to the `htdocs` folder.

6: Open the `web` folder and move all the files to the `htdocs` folder.

7: Your `htdocs` folder should look like this:
```
    htdocs
    ├── 📂 assets
    ├── 📂 cdn
    ├── 📂 server-emulator
    ├── 📄 char-detail.html
    ├── 📄 index.html
    ├── 📄 lost-password.html
    ├── 📄 manage-account.html
    ├── 📄 play.html
    ├── 📄 setup.html
    ├── 📄 signup.html
    ├── 📄 tos.html
```

8. (Optional) Download the offline gamefiles and extract them to `htdocs/cdn/gamefiles/`.

### 2. Initial setup:

Decide if you want to use MySQL or SQLite. If you want to store the data in a single file (more easy), use SQLite.

If you want to use a faster database, use MySQL. Is recommended to use MySQL.

1. Start the Apache service in XAMPP. If you want to use MySQL, start the MySQL service as well.

2. If you want to use SQLite, skit to step 4. If you want to use MySQL, open your browser and go to `http://localhost/phpmyadmin/`.

3. Create a new database with any name you want. I will use `dfps` as an example.

4. Access the setup page at `http://localhost/setup.html` in your browser.

5. If you want to use SQLite, you can specify a path to the SQLite database file. You can let it empty to use the default path. By default, the SQLite database file will be created in `server-emulator/data/` folder, it will be the `db.sqlite3` file.

6. If you want to use MySQL, you need to specify the MySQL host, username, password, and database name. The default values for XAMPP are:
    - Host: `localhost`
    - Username: `root`
    - Password: `` (empty)
    - Database: `dfps` (the database name you created in step 3)

7. Click the `Setup` button and wait some minutes. The server will create the necessary tables and will add all the game data.

8. After the setup is complete, you can play the game at `http://localhost/`.

### 3. Usage:

Every time you want to play the game, you need to start the Apache service in XAMPP. If you are using MySQL, you need to start the MySQL service as well.

If you are using MySQL, you can manage the database using phpMyAdmin at `http://localhost/phpmyadmin/`. You can add coins, gold, DA, and do more things using this.

If you are using SQLite, you can manage the database using the [DB Browser for SQLite](https://sqlitebrowser.org/). You can open the `server-emulator/data/db.sqlite3` file using this program.

### 4. Upgrading the server:

If you want to upgrade the server to a newer version, you can follow the steps below:

1. If you are using SQLite, backup the `db.sqlite3` file. If you used the default path, this file is located in `server-emulator/data/db.sqlite3`.

2. Delete all the files in the `htdocs` folder.

3. Do the same steps 4 to 8 in the `Prepare files` section.

4. If you are using SQLite, move the `db.sqlite3` file you backed up to the old path.

5. Access the setup page at `http://localhost/setup.html` in your browser.

6. Select the database type you are using, fill the fields with the same values you used before.

7. Click the `Setup` button and wait some minutes. The server will create the necessary tables and will add all the game data.

8. After the setup is complete, you can play the game at `http://localhost/`.

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
        Read the `/src/server-emulator/.config.default.php` file and set the environment variables accordingly.
    - Using a .config.php file:
        Copy the `/src/server-emulator/.config.default.php` to `/src/server-emulator/.config.php` and edit the values as needed.

    You can use MySQL or SQLite. See `/src/server-emulator/.config.default.php` for more details.

    **Note**: If you don't create the `.config.php` file, the server will use the default system environment variables.

3. (Optional) Download the offline gamefiles from [mediafire, click here](https://www.mediafire.com/file/7ce4vkkwokmx2h1/gamefiles.zip/file) (updated at 2025-04-05) and extract it to `/src/cdn/gamefiles/`\
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

4. (Optional) Download the offline gamefiles from [mediafire, click here](https://www.mediafire.com/file/7ce4vkkwokmx2h1/gamefiles.zip/file) (updated at 2025-04-05) and extract it to `public_html/cdn/gamefiles/`\
    **Note**: If you don't download the gamefiles, the server will progressively download them as each game file is requested **only if you use the `cache` mode at `gamefilesPath` settings**. This means that as you play, the server will fetch the necessary files in real-time, ensuring you can continue playing without interruption.

### Usage:

1. The server should be running.

2. Access the game using your domain in your browser.

3. Setup the database using [upgrade tool](UPGRADE.md).

4. Now you can play the game!
