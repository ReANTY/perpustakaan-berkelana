<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('books', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('title');
            $table->string('author');
            $table->string('publisher')->nullable();
            $table->year('publication_year')->nullable();
            $table->string('category');
            $table->text('synopsis')->nullable();
            $table->string('cover_image')->nullable();
            $table->enum('status', ['available', 'booked', 'borrowed', 'unavailable'])->default('available');
            $table->boolean('is_featured')->default(false);
            $table->integer('lapak_request_count')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('books');
    }
};
