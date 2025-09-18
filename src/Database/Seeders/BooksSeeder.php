<?php

namespace App\Database\Seeders;

use App\Models\Books;

class BooksSeeder
{
    public static function run()
    {
        $books = [
            ['bookTitle' => '1984', 'bookAuthor' => 'George Orwell', 'bookPublishYear' => 1949],
            ['bookTitle' => 'Brave New World', 'bookAuthor' => 'Aldous Huxley', 'bookPublishYear' => 1932],
            ['bookTitle' => 'The Great Gatsby', 'bookAuthor' => 'F. Scott Fitzgerald', 'bookPublishYear' => 1925],
        ];

        foreach ($books as $book) {
            $exists = Books::where('bookTitle', $book['bookTitle'])->first();
            if (!$exists) {
                Books::create($book);
                echo "Inserted book: {$book['bookTitle']}\n";
            }
        }
    }
}
