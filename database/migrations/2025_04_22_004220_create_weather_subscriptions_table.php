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
        Schema::create('weather_subscriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('city_id')->constrained()->onDelete('cascade');
            $table->string('email');
            $table->string('condition_type'); // e.g., 'temperature_below', 'rain', 'snow'
            $table->decimal('condition_value', 8, 2)->nullable(); // e.g., temperature threshold
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique(['user_id', 'city_id', 'condition_type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('weather_subscriptions');
    }
};
