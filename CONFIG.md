
# DragonFable Private Server - Configuration

You can configure the server connection by editing the `/src/server-emulator/.env.php` file or by setting your environment variables like this file.

## Game Settings

Access your database and open your `settings` table. This is what each column means:

- `id`: The `id` parameter is used to identify the settings. You can have multiple settings, each with a different `id`. The default `id` is `1`. To specify what settings to use, you can change the `DF_SETTINGS_ID` environment variable. It can be pretty cool if you use a production server and a staging server, for example. The staging server can have a different `id` and you can test some features with the same production data.

- `gameSwf`: The `gameSwf` parameter is used to specify the game file to load. The default is `game15_9_04-patched.swf`. You can change it to any other game file you want to use, if you use a custom patched version, for example or you can use a specific other version.

- `serverVersion`: The `serverVersion` parameter is used to specify the game build version string. It is not used, you can use it to specify any string you want.

- `serverLocation`: Like the `gameSwf`, the `serverLocation` parameter is used to specify the server to serve the game. The default is `server-emulator/server.php/`. You can change it to any other server you want to use, if you use a custom server file, for example. You can use a custom domain, like `https://server.example.org/server.php/`. CORS headers may be required.

- `gamefilesPath`: This is the path to the game files. It has 3 modes, you can use what you want:
    - `local`: Use local game files. If not found, it will return a 404 error.
    - `dynamic`: Like `local`, but if the file is not found, it will fetch it from the remote server. It will not save the file locally.
    - `cache`: Like `dynamic`, but it will save the file locally. It will serve the file faster in the future.
    - `update`: Like `cache`, but it will fetch the file from the remote server and save it locally. If the original file is updated, it will fetch the new file. It will serve the file faster in the future if you use `cache`. This mode is used when `convert` dev-tool is used only with interface.
    - The `remote` mode exists, but you should not use it, as it will fetch all files from the remote server, and they are not patched.

    You can also use a custom domain, like `https://cdn.example.org/gamefiles/cache.php/`. CORS headers may be required.

    I recommend using the `cache` mode, as it will save the files locally and serve them faster in the future, but if space is a concern, you can use the `dynamic` mode.

- `homeUrl`, `playUrl`, `signUpUrl`, `lostPasswordUrl`, `tosUrl`: These parameters are used to specify the URLs for the home, play, sign up, lost password, and terms of service pages. You can use any URL you want. You can use relative URLs, like `../../../index.html`, or absolute URLs, like `https://example.org/index.html`.

- `signUpMessage`: The `signUpMessage` parameter is used to specify the message that will be shown in the sign-up page.

- `news`: The `news` parameter is used to specify the news that will be shown when user logs in.

- `enableAdvertising`: The `enableAdvertising` parameter is used to enable or disable ads. If you want to enable ads, set it to `true`, otherwise set it to `false`. I think this feature is not working, as the game is not loading the ads.

- `dailyQuestCoinsReward`: The `dailyQuestCoinsReward` parameter is used to specify the coins reward for daily quests. The default is `3`.

- `revalidateClientValues`: The `revalidateClientValues` parameter is used to revalidate the client values, for example, if the client sends a value that is not expected, it will be revalidated. If user try to sell a item for 999999 gold or dragon coins, we will revalidate and sell with the correct price. It can have unexpected behavior. If you want to revalidate, set it to `true`, otherwise set it to `false`.

- `banInvalidClientValues`: The `banInvalidClientValues` parameter is used to ban users that send invalid values. Like the `revalidateClientValues`, if the user try to sell a item for 999999 gold or dragon coins, we will revalidate and if the value is unexpected, we will ban the user. If you want to ban, set it to `true`, otherwise set it to `false`. As the `revalidateClientValues` can have unexpected behavior, It can cause some users to be banned injustly. If you want to ban, I recommend to set the `revalidateClientValues` to `true` and the `banInvalidClientValues` to `false`.

- `canDeleteUpgradedChar`: The `canDeleteUpgradedChar` parameter is used to allow the user to delete an upgraded character. The original game does not allow the user to delete an specific upgraded character. User can only delete a character if it is not upgraded or when the entire account is upgraded. If you want to allow the user to delete an upgraded character, set it to `true`, otherwise set it to `false`.

- `nonUpgradedChars`, `upgradedChars`, `nonUpgradedMaxBagSlots`, `upgradedMaxBagSlots`, `nonUpgradedMaxBankSlots`, `upgradedMaxBankSlots`, `nonUpgradedMaxHouseSlots`, `upgradedMaxHouseSlots`, `nonUpgradedMaxHouseItemSlots`, `upgradedMaxHouseItemSlots`: These parameters are used to specify the number of characters, bag slots, bank slots, house slots, and house item slots for non-upgraded and upgraded accounts. You can change the values as you want.

- `experienceMultiplier`, `gemsMultiplier`, `goldMultiplier`, `silverMultiplier`: These parameters are used to specify the experience, gems, gold, and silver multipliers. You can change the values as you want. I never have seen the gems and silver currencies in the game, but they are in the database.

- `onlineTimeout`: The `onlineTimeout` parameter is used to specify the online timeout to count in website. The default is `10` minutes.

- `detailed404ClientError`: The `detailed404ClientError` parameter is used to specify if the server should return a detailed 404 client error. If you want to return a detailed 404 client error, set it to `true`, otherwise set it to `false`. It can be useful to debug, but it can expose some information to the client and can confuse the user.

