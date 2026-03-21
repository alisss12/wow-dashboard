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
            $table->foreignId('advertiser_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('buyer_realm')->nullable();
            $table->string('buyer_faction')->nullable();
            $table->decimal('agreed_price', 10, 2)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('raid_signups', function (Blueprint $table) {
            $table->dropForeign(['advertiser_user_id']);
            $table->dropColumn(['advertiser_user_id', 'buyer_realm', 'buyer_faction', 'agreed_price']);
        });
    }
};
