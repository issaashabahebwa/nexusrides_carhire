<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vehicles', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('brand');
            $table->string('model');
            $table->year('year');
            $table->string('plate_number')->unique();
            $table->enum('type', ['sedan', 'suv', 'van', 'truck', 'bus', 'convertible', 'pickup'])->default('sedan');
            $table->integer('seats')->default(5);
            $table->decimal('price_per_day', 10, 2);
            $table->text('description')->nullable();
            $table->string('image')->nullable();
            $table->string('color')->nullable();
            $table->enum('transmission', ['automatic', 'manual'])->default('automatic');
            $table->string('fuel_type')->default('petrol');
            $table->enum('status', ['available', 'hired', 'maintenance', 'retired'])->default('available');
            $table->string('location')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vehicles');
    }
};
