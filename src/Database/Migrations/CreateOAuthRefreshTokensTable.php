<?php
use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Schema\Blueprint;

class CreateOauthRefreshTokensTable
{
    public function up()
    {
        if (!Capsule::schema()->hasTable('oauth_refresh_tokens')) {
            Capsule::schema()->create('oauth_refresh_tokens', function (Blueprint $table) {
                $table->string('id', 100)->primary();
                $table->string('access_token_id', 100);
                $table->boolean('revoked')->default(false);
                $table->timestamp('expires_at');
                $table->timestamp('created_at')->default(Capsule::raw('CURRENT_TIMESTAMP'));
                $table->timestamp('updated_at')->default(Capsule::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));

                // Foreign key
                $table->foreign('access_token_id')->references('id')->on('oauth_access_tokens')->onDelete('cascade');
            });
            echo "books oauth_refresh_tokens created.\n";
        }else{
            echo "books oauth_refresh_tokens already exist.\n";
        }
    }

    public function down()
    {
        Capsule::schema()->dropIfExists('oauth_refresh_tokens');
    }
}
