<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('borrowings', function (Blueprint $table) {
            $table->foreignId('from_reservation_id')
                ->after('book_id')
                ->nullable()
                ->constrained('reservations')
                ->onDelete('set null')
                ->comment('Link to reservation if this borrowing was created from reservation');
        });
    }

    public function down()
    {
        Schema::table('borrowings', function (Blueprint $table) {
            $table->dropConstrainedForeignId('from_reservation_id');
        });
    }
};
