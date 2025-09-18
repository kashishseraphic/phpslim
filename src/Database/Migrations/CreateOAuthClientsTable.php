<?php
use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Schema\Blueprint;

class CreateOauthClientsTable
{
    public function up()
    {
        if (!Capsule::schema()->hasTable('oauth_clients')) {
            Capsule::schema()->create('oauth_clients', function (Blueprint $table) {
                $table->string('client_id', 80)->primary();
                $table->string('client_secret', 255)->nullable();
                $table->string('redirect_uri', 2000)->nullable();
                $table->string('grant_types', 80)->nullable();
                $table->string('scope', 4000)->nullable();
                $table->string('user_id', 80)->nullable();
                $table->string('name', 255);
                $table->boolean('is_confidential')->default(false);
                $table->timestamp('created_at')->default(Capsule::raw('CURRENT_TIMESTAMP'));
                $table->timestamp('updated_at')->default(Capsule::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
            });
            echo "books oauth_clients created.\n";
        }else{
            echo "books oauth_clients already exist.\n";
        }
    }

    public function down()
    {
        Capsule::schema()->dropIfExists('oauth_clients');
    }
}
