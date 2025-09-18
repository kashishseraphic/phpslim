<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OauthRefreshToken extends Model
{
    protected $table = 'oauth_refresh_tokens';
    protected $primaryKey = 'id';
    public $incrementing = false; // primary key is string
    public $timestamps = true;

    protected $fillable = [
        'id',
        'access_token_id',
        'revoked',
        'expires_at',
    ];

    // Relationships
    public function accessToken()
    {
        return $this->belongsTo(OauthAccessToken::class, 'access_token_id', 'id');
    }
}
