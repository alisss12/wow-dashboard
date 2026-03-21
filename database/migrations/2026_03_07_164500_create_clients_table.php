<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->string('character_name');
            $table->string('realm')->nullable();
            $table->string('region')->default('EU');
            $table->enum('type', ['vip', 'safe', 'unsafe'])->default('safe');
            $table->integer('orders_count')->default(0);
            $table->decimal('total_spent', 15, 2)->default(0);
            $table->text('flag_reason')->nullable();
            $table->string('discord_id')->nullable();
            $table->timestamps();
            
            $table->unique(['character_name', 'realm', 'region']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('clients');
    }
};
