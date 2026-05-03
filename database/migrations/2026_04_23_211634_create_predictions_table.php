<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('predictions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('coin');           // e.g. 'bitcoin'
            $table->decimal('current_price', 20, 8);
            $table->decimal('predicted_price', 20, 8);
            $table->decimal('mae', 20, 8);
            $table->decimal('rmse', 20, 8);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('predictions');
    }
};
