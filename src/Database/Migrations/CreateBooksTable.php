<?php
use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Schema\Blueprint;

class CreateBooksTable
{
    public function up()
    {
        if (!Capsule::schema()->hasTable('books')) {
            Capsule::schema()->create('books', function (Blueprint $table) {
                $table->increments('bookId');
                $table->string('bookTitle');
                $table->string('bookAuthor')->nullable();
                $table->integer('bookPublishYear')->nullable();
            });

            // Add FULLTEXT index
            Capsule::statement('ALTER TABLE books ADD FULLTEXT fulltext_index(bookTitle, bookAuthor)');
            echo "books table created.\n";
        }else{
            echo "books table already exist.\n";
        }
    }

    public function down()
    {
        Capsule::schema()->dropIfExists('books');
    }
}
