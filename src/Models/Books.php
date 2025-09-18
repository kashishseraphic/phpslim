<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Books extends Model
{
    // Table name
    protected $table = 'books';

    // Primary key
    protected $primaryKey = 'bookId';

    // Disable timestamps (since your table has no created_at/updated_at)
    public $timestamps = false;

    // Fields that can be mass-assigned
    protected $fillable = [
        'bookTitle',
        'bookAuthor',
        'bookPublishYear',
    ];

    // Optional: if you want to hide some fields when converting to JSON
    // protected $hidden = [];

    /**
     * Define one-to-many relationship with BorrowLog
     */
    public function borrowLogs()
    {
        return $this->hasMany(BorrowLog::class, 'bookId', 'bookId');
    }

    /**
     * The user associated with this borrow log.
     */
    public function user()
    {
        return $this->belongsTo(Users::class, 'userId', 'userId');
    }

}
