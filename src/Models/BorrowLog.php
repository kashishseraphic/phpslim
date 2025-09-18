<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BorrowLog extends Model
{
    // Table name
    protected $table = 'borrowlog';

    // Primary key
    protected $primaryKey = 'borrowLogId';

    // Disable timestamps (since your table has custom datetime column)
    public $timestamps = false;

    // Fields that can be mass-assigned
    protected $fillable = [
        'bookId',
        'userId',
        'borrowLogDateTime',
    ];

    // Optional: define relationships

    /**
     * The book associated with this borrow log.
     */
    public function book()
    {
        return $this->belongsTo(Books::class, 'bookId', 'bookId');
    }

    /**
     * The user associated with this borrow log.
     */
    public function user()
    {
        return $this->belongsTo(Users::class, 'userId', 'userId');
    }
}
