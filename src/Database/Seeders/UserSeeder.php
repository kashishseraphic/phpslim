<?php

namespace App\Database\Seeders;

use App\Models\Users;

class UserSeeder
{
    public static function run()
    {
        $users = [
            [
                'username' => 'alice',
                'passwordHash' => password_hash('password', PASSWORD_BCRYPT),
            ],
            [
                'username' => 'bob',
                'passwordHash' => password_hash('password', PASSWORD_BCRYPT),
            ],
            [
                'username' => 'charlie',
                'passwordHash' => password_hash('password', PASSWORD_BCRYPT),
            ],
        ];

        foreach ($users as $user) {
            // Check if user already exists
            if (!Users::where('username', $user['username'])->exists()) {
                Users::create($user);
            }
        }

        echo "Users seeded successfully.\n";
    }
}
