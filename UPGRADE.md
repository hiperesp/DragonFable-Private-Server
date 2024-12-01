# DragonFable Private Server - Upgrading

## New server files

Do the same step as [Setup](SETUP.md) but with the new version of the server.

Don't forget to backup your `.env.php` file if you have any custom settings. Backup also your database file if you are using SQLite, by default the file is `db.sqlite3`.

## New data

Ensure that your `server-emulator/setup.lock` file is removed, so the server will run the setup again.

Go to `/server-emulator/server.php/dev/` in your browser and click on `Setup/upgrade server` to apply the new data.