<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('holiday_settings', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->date('date_start');
            $table->date('date_end');
            $table->boolean('is_active')->default(true);
            $table->string('animation_type');
            $table->json('animation_config')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('holiday_settings');
    }
};

