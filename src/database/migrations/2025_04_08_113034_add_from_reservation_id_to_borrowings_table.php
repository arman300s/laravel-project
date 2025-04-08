<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('borrowings', function (Blueprint $table) {
            $table->foreignId('from_reservation_id')
                ->nullable()
                ->constrained('reservations')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('borrowings', function (Blueprint $table) {
            $table->dropForeign(['from_reservation_id']);
            $table->dropColumn('from_reservation_id');
        });
    }
};
