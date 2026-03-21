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
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedBigInteger('game_account_id')->nullable()->after('id')->index();
            $table->integer('vote_points')->default(0)->after('password');
            $table->integer('donation_points')->default(0)->after('vote_points');
            $table->string('referral_code', 20)->nullable()->unique()->after('donation_points');
            $table->unsignedBigInteger('referred_by')->nullable()->index()->after('referral_code');
            $table->timestamp('last_vote_at')->nullable()->after('referred_by');
            
            // Note: In Phase 4, we will potentially setup foreign keys against the auth db
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'game_account_id',
                'vote_points',
                'donation_points',
                'referral_code',
                'referred_by',
                'last_vote_at'
            ]);
        });
    }
};
