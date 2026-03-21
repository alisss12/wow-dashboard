<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('booster_applications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('character_name');
            $table->string('realm');
            $table->string('class');
            $table->string('spec');
            $table->json('roles'); // ['tank', 'healer', 'dps']
            $table->text('experience');
            $table->string('logs_url')->nullable();
            $table->string('status')->default('pending'); // pending, approved, rejected
            $table->text('staff_notes')->nullable();
            $table->timestamps();
        });

        Schema::create('boosting_services', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('category'); // Raid, M+, PvP, Other
            $table->decimal('base_price', 15, 0);
            $table->integer('required_boosters')->default(0);
            $table->integer('max_clients')->default(0);
            $table->integer('required_tanks')->default(0);
            $table->integer('required_healers')->default(0);
            $table->integer('required_dps')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('booster_applications');
        Schema::dropIfExists('boosting_services');
    }
};
