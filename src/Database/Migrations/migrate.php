<?php

require_once __DIR__ . '/../../../vendor/autoload.php';

$migrations = [
    'CreateUsersTable',
    'CreateBooksTable',
    'CreateBorrowLogTable',
    'CreateOAuthClientsTable',
    'CreateOAuthAccessTokensTable',
    'CreateOAuthRefreshTokensTable',
];

foreach ($migrations as $migrationClass) {
    require_once __DIR__ . "/{$migrationClass}.php";
    $migration = new $migrationClass();
    $migration->up();
}
