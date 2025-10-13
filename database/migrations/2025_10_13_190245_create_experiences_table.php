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
        Schema::create('experiences', function (Blueprint $table) {
            $table->id();
            
            // Informations principales
            $table->string('title');
            $table->string('company');
            $table->string('location')->nullable();
            $table->string('company_logo')->nullable();
            
            // Type d'expérience
            $table->enum('type', ['work', 'education', 'certification'])->default('work');
            // work = Expérience pro, education = Formation, certification = Certification
            
            // Dates
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->boolean('is_current')->default(false);
            
            // Description
            $table->text('description')->nullable();
            $table->json('tasks')->nullable();
            $table->json('technologies')->nullable();
            
            // Liens
            $table->string('company_url')->nullable();
            $table->string('certificate_url')->nullable();
            
            // Affichage
            $table->boolean('is_visible')->default(true);
            $table->integer('order')->default(0);
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('experiences');
    }
};