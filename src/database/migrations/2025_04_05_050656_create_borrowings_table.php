<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('borrowings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('book_id')->constrained()->onDelete('cascade');
            $table->timestamp('borrowed_at');
            $table->timestamp('returned_at')->nullable();
            $table->timestamp('due_at');
            $table->text('description')->nullable();
            $table->timestamps();
            $table->index('status');
            $table->index('due_at');
            $table->string('status', 20);
        });
    }

    public function down()
    {
        Schema::dropIfExists('borrowings');
    }
};
