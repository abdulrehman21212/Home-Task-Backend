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
        Schema::create('articles', function (Blueprint $table) {
            $table->id();
            $table->text('author')->nullable();
            $table->text('title')->nullable();
            $table->text('description')->nullable();
            $table->string('url')->nullable();
            $table->text('image_url')->nullable();
            $table->dateTime('published_at')->nullable();
            $table->string('source')->nullable();
            $table->string('section')->nullable();
            $table->string('sub_section')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('articles');
    }
};
