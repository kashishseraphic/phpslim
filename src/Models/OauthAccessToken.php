<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OauthAccessToken extends Model
{
    protected $table = 'oauth_access_tokens';
    protected $primaryKey = 'id';
    public $incrementing = false; // primary key is string
    public $timestamps = true;

    protected $fillable = [
        'id',
        'user_id',
        'client_id',
        'scopes',
        'revoked',
        'expires_at',
    ];

    // Relationships
    public function client()
    {
        return $this->belongsTo(OauthClient::class, 'client_id', 'client_id');
    }

    public function refreshTokens()
    {
        return $this->hasMany(OauthRefreshToken::class, 'access_token_id', 'id');
    }
}
