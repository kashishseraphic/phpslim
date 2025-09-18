<?php
use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Schema\Blueprint;

class CreateBorrowLogTable
{
    public function up()
    {
        if (!Capsule::schema()->hasTable('borrowlog')) {
            Capsule::schema()->create('borrowlog', function (Blueprint $table) {
                $table->increments('borrowLogId');
                $table->unsignedInteger('bookId');
                $table->unsignedInteger('userId');
                $table->dateTime('borrowLogDateTime')->default(Capsule::raw('CURRENT_TIMESTAMP'));

                // Foreign keys
                $table->foreign('bookId')->references('bookId')->on('books')->onDelete('cascade');
                $table->foreign('userId')->references('userId')->on('users')->onDelete('cascade');
            });
            echo "books borrowlog created.\n";
        }else{
            echo "books borrowlog already exist.\n";
        }
    }

    public function down()
    {
        Capsule::schema()->dropIfExists('borrowlog');
    }
}
