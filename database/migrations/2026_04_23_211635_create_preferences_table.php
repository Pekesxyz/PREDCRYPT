<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('preferences', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('favorite_coin', 50);  // e.g. 'bitcoin'
            $table->timestamps();

            $table->unique(['user_id', 'favorite_coin']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('preferences');
    }
};
