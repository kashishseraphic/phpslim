<?php

namespace App\Database\Seeders;

use App\Models\BorrowLog;
use App\Models\Books;
use App\Models\Users;

class BorrowLogSeeder
{
    public static function run()
    {
        // Example: create some borrow logs
        $users = Users::all();
        $books = Books::all();

        foreach ($users as $user) {
            foreach ($books as $book) {
                $exists = BorrowLog::where([
                    'userId' => $user->userId,
                    'bookId' => $book->bookId
                ])->first();

                if (!$exists) {
                    BorrowLog::create([
                        'userId' => $user->userId,
                        'bookId' => $book->bookId,
                        'borrowLogDateTime' => date('Y-m-d H:i:s'),
                    ]);
                    echo "Inserted borrow log: User {$user->username} borrowed {$book->bookTitle}\n";
                }
            }
        }
    }
}
