<?php
require __DIR__ . '/../../../vendor/autoload.php';

// Load Capsule
$db = require __DIR__ . '/../../..//config/database.php';

// Load the seeder
use App\Database\Seeders\UserSeeder;
use App\Database\Seeders\OauthClientSeeder;
use App\Database\Seeders\BooksSeeder;
use App\Database\Seeders\BorrowLogSeeder;


OauthClientSeeder::run();
UserSeeder::run();
BooksSeeder::run();
BorrowLogSeeder::run();
