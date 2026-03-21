<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('raid_signups', function (Blueprint $table) {
            $table->boolean('is_paid')->default(false);
            $table->timestamp('paid_at')->nullable();
            $table->foreignId('collector_user_id')->nullable()->constrained('users');
            $table->string('payment_method')->nullable(); // Trade, Mail
        });

        Schema::table('raid_events', function (Blueprint $table) {
            $table->integer('required_tanks')->default(0);
            $table->integer('required_healers')->default(0);
            $table->integer('required_dps')->default(0);
            $table->foreignId('service_id')->nullable()->constrained('boosting_services');
        });
    }

    public function down(): void
    {
        Schema::table('raid_signups', function (Blueprint $table) {
            $table->dropColumn(['is_paid', 'paid_at', 'collector_user_id', 'payment_method']);
        });

        Schema::table('raid_events', function (Blueprint $table) {
            $table->dropColumn(['required_tanks', 'required_healers', 'required_dps', 'service_id']);
        });
    }
};
