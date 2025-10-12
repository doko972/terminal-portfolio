<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description');
            $table->text('technologies'); // JSON stocké en texte
            $table->string('image')->nullable();
            $table->string('url')->nullable();
            $table->string('github_url')->nullable();
            $table->enum('status', ['en_cours', 'termine', 'archive'])->default('termine');
            $table->date('completed_at')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->integer('order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};