<?php

require_once __DIR__ . '/../../../vendor/autoload.php';
require_once __DIR__ . '/../../../config/database.php'; // This contains $capsule setup

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Schema\Blueprint;

class CreateUsersTable
{
    public function up()
    {
        if (!Capsule::schema()->hasTable('users')) {
            Capsule::schema()->create('users', function (Blueprint $table) {
                $table->increments('userId');
                $table->string('username')->unique();
                $table->string('passwordHash');
            });
            echo "users table created.\n";
        }else{
            echo "users table already exist.\n";
        }
    }

    public function down()
    {
        Capsule::schema()->dropIfExists('users');
        echo "users table dropped.\n";
    }
}
