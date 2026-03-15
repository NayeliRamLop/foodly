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
        Schema::create('recipes', function (Blueprint $table) {
            $table->id();
            
    $table->tinyInteger('status')->default(1); 
    $table->string('recipe_title');
    $table->text('recipe_description');
    $table->text('ingredients');
    $table->text('instructions');
    $table->integer('preparation_time');
    $table->integer('cooking_timer');
    $table->string('difficulty');
    $table->foreignId('user_id')->constrained();
    $table->foreignId('category_id')->constrained('categories');
    $table->foreignId('subcategory_id')->nullable()->constrained('subcategories');
    $table->string('image')->nullable();
    $table->string('video')->nullable();
    $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recipes');
        
    }
};
