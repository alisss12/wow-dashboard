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
        Schema::table('raid_signups', function (Blueprint $table) {
            $table->string('attendance_status')->default('present')->after('status'); // present, late, absent
            $table->decimal('payout_amount', 12, 2)->default(0)->after('attendance_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('raid_signups', function (Blueprint $table) {
            $table->dropColumn(['attendance_status', 'payout_amount']);
        });
    }
};
