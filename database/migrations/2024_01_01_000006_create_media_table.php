<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('media', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('filename');
            $table->string('path');
            $table->string('type')->default('image');
            $table->bigInteger('size');
            $table->string('mime_type');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('gallery_id')->nullable()->constrained('media_galleries')->onDelete('set null');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('media');
    }
};

