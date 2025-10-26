<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            $table->dropColumn('status');
            $table->enum('status', ['pending', 'confirmed', 'completed', 'canceled', 'rescheduled'])->default('pending')->after('appointment_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            $table->dropColumn('status');
            $table->enum('status', ['pending', 'confirmed', 'completed', 'canceled'])->default('pending')->after('appointment_date');
        });
    }
};
