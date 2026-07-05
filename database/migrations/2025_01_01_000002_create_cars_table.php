<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cars', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('make');
            $table->string('model');
            $table->integer('year');
            $table->decimal('price', 12, 2);
            $table->integer('mileage');
            $table->enum('fuel_type', ['petrol', 'diesel', 'electric', 'hybrid']);
            $table->enum('transmission', ['manual', 'automatic']);
            $table->string('city');
            $table->text('description')->nullable();
            $table->enum('status', ['draft', 'pending', 'approved', 'rejected'])->default('draft');
            $table->text('rejection_reason')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->timestamp('featured_at')->nullable();
            $table->timestamp('featured_until')->nullable();
            $table->unsignedInteger('views_count')->default(0);
            $table->string('slug')->unique();
            $table->timestamps();

            $table->index(['status', 'created_at']);
            $table->index(['user_id', 'status']);
            $table->index(['make', 'model']);
            $table->index('city');
            $table->index('price');
            $table->index('is_featured');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cars');
    }
};
