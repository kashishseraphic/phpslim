<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Users extends Model
{
    // Table name
    protected $table = 'users';

    // Primary key
    protected $primaryKey = 'userId';

    // Disable timestamps
    public $timestamps = false;

    // Fields that can be mass-assigned
    protected $fillable = [
        'username',
        'passwordHash',
    ];

    // // Hide sensitive fields when converting to array/JSON
    // protected $hidden = [
    //     'passwordHash',
    // ];
}
