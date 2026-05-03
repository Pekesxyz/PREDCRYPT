<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('price_alerts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('coin');           // e.g. 'bitcoin'
            $table->decimal('target_price', 20, 8);
            $table->boolean('is_triggered')->default(false);
            $table->string('direction')->default('above'); // 'above' or 'below'
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('price_alerts');
    }
};
