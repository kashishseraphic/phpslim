<?php

namespace App\Database\Seeders;

use App\Models\OauthClient;

class OauthClientSeeder
{
    public static function run()
    {
        // Check if client already exists
        $existing = OauthClient::find('test-client');
        if ($existing) {
            echo "Client 'test-client' already exists.\n";
            return;
        }

        // Insert default client
        OauthClient::create([
            'client_id' => 'test-client',
            'client_secret' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // bcrypt hash
            'name' => 'Test Client',
            'is_confidential' => true,
            'redirect_uri' => null,
            'grant_types' => null,
            'scope' => null,
            'user_id' => null,
        ]);

        echo "Client 'test-client' created successfully.\n";

    }
}
