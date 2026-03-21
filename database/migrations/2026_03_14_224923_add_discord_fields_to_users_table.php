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
            $table->string('discord_id')->nullable()->unique()->after('id');
            $table->string('discord_name')->nullable()->after('discord_id');
            $table->string('avatar')->nullable()->after('discord_name');
            $table->text('oauth_token')->nullable()->after('password');
            $table->text('oauth_refresh_token')->nullable()->after('oauth_token');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['discord_id', 'discord_name', 'avatar', 'oauth_token', 'oauth_refresh_token']);
        });
    }
};
