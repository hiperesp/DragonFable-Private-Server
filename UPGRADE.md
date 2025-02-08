# DragonFable Private Server - Upgrading

## New server files

Do the same step as [Setup](SETUP.md) but with the new version of the server.

Don't forget to backup your `.config.php` file if you have any custom settings. Backup also your database file if you are using SQLite, by default the file is `db.sqlite3`.

## New data

Ensure that your `server-emulator/setup.lock` file is removed, so the server will run the setup again.
