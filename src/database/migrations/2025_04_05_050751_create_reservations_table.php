<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('reservations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('book_id')->constrained()->onDelete('cascade');
            $table->timestamp('reserved_at');
            $table->timestamp('expires_at');
            $table->enum('status', [
                'pending',
                'completed',
                'canceled',
                'active',
            ])->default('pending');
            $table->text('description')->nullable();
            $table->timestamps();

            $table->index('status');
            $table->index('expires_at');
        });
    }

    public function down()
    {
        Schema::dropIfExists('reservations');
    }
};
