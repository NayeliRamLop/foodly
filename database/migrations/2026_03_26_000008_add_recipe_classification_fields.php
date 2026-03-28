<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('recipes', function (Blueprint $table) {
            $table->string('dish_type')->nullable()->after('brand');
            $table->string('daily_category')->nullable()->after('dish_type');
            $table->string('special_occasion')->nullable()->after('daily_category');
            $table->string('baking_category')->nullable()->after('special_occasion');
            $table->string('seasonality')->nullable()->after('baking_category');
            $table->string('preparation_method')->nullable()->after('seasonality');
        });
    }

    public function down(): void
    {
        Schema::table('recipes', function (Blueprint $table) {
            $table->dropColumn([
                'dish_type',
                'daily_category',
                'special_occasion',
                'baking_category',
                'seasonality',
                'preparation_method',
            ]);
        });
    }
};
