<?php
use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Schema\Blueprint;

class CreateOauthAccessTokensTable
{
    public function up()
    {
        if (!Capsule::schema()->hasTable('oauth_access_tokens')) {
            Capsule::schema()->create('oauth_access_tokens', function (Blueprint $table) {
                $table->string('id', 100)->primary();
                $table->string('user_id', 80)->nullable();
                $table->string('client_id', 80);
                $table->text('scopes')->nullable();
                $table->boolean('revoked')->default(false);
                $table->timestamp('created_at')->default(Capsule::raw('CURRENT_TIMESTAMP'));
                $table->timestamp('updated_at')->default(Capsule::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
                $table->timestamp('expires_at');

                // Foreign key
                $table->foreign('client_id')->references('client_id')->on('oauth_clients')->onDelete('cascade');
            });
            echo "books oauth_access_tokens created.\n";
        }else{
            echo "books oauth_access_tokens already exist.\n";
        }
    }

    public function down()
    {
        Capsule::schema()->dropIfExists('oauth_access_tokens');
    }
}
