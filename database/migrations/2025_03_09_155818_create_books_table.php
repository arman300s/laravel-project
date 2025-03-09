<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBooksTable extends Migration
{
    /**
     * Выполнить миграцию.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('books', function (Blueprint $table) {
            $table->id();  // Столбец для уникального идентификатора (ID)
            $table->string('title');  // Столбец для названия книги
            $table->string('author');  // Столбец для автора книги
            $table->text('description');  // Столбец для описания книги
            $table->boolean('is_active')->default(true);  // Столбец для активности книги, по умолчанию true
            $table->timestamps();  // Столбцы для времени создания и обновления записи
        });
    }

    /**
     * Откатить миграцию.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('books');  // Удалить таблицу books
    }
}
