<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OauthClient extends Model
{
    protected $table = 'oauth_clients';
    protected $primaryKey = 'client_id';
    public $incrementing = false; // primary key is string
    public $timestamps = true; // uses created_at, updated_at

    protected $fillable = [
        'client_id',
        'client_secret',
        'redirect_uri',
        'grant_types',
        'scope',
        'user_id',
        'name',
        'is_confidential',
    ];

    // Relationships
    public function accessTokens()
    {
        return $this->hasMany(OauthAccessToken::class, 'client_id', 'client_id');
    }
}
