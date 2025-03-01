<?php declare(strict_types=1);
namespace hiperesp\server\controllers\setup;

use hiperesp\server\controllers\Controller;
use hiperesp\server\attributes\Inject;
use hiperesp\server\attributes\Request;
use hiperesp\server\enums\Input;
use hiperesp\server\enums\Output;
use hiperesp\server\services\SetupService;
use hiperesp\server\services\WebStatsService;

class SetupController extends Controller {

    #[Inject] private WebStatsService $webStatsService;
    #[Inject] private SetupService $setupService;

    #[Request(
        endpoint: '/setup/defaults',
        inputType: Input::JSON,
        outputType: Output::JSON
    )]
    public function defaultParams(): array {
        global $base;

        $sqliteDriver = \json_encode("\\".\hiperesp\server\storage\SQLite::class);

        return [
            "[data-if-driver={$sqliteDriver}] [name='location']" => $base."/data/db.sqlite3",
        ];
    }

    #[Request(
        endpoint: '/setup/create-config',
        inputType: Input::JSON,
        outputType: Output::JSON
    )]
    public function createConfig(array $input): array {
        try {
            $this->setupService->createConfig([
                "DB_DRIVER" => $input["DB_DRIVER"],
                "DB_OPTIONS" => $input["DB_OPTIONS"]
            ]);
            return [
                "success" => true,
                "message" => "The server is successfully setup. Now you can finish the setup by creating the database schema."
            ];
        } catch(\Exception $e) {
            return [
                "success" => false,
                "message" => $e->getMessage()
            ];
        }
    }

    #[Request(
        endpoint: '/setup/upgrade-database',
        inputType: Input::NONE,
        outputType: Output::JSON
    )]
    public function upgradeDatabase(): array {
        \ignore_user_abort(true);
        \set_time_limit(0);

        try {
            $this->setupService->upgradeDatabase();
            return [
                "success" => true,
                "message" => "The database is successfully upgraded."
            ];
        } catch(\Exception $e) {
            return [
                "success" => false,
                "message" => $e->getMessage()
            ];
        }
    }

}