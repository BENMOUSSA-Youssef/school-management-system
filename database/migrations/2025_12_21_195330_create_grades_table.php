<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    
    public function up(): void
    {
        Schema::create('grades', function (Blueprint $table) {
            $table->id();
            // This links to the ID of the student
            $table->foreignId('student_id')->constrained()->onDelete('cascade');
            // This links to the ID of the module
            $table->foreignId('module_id')->constrained()->onDelete('cascade');
            $table->decimal('score', 5, 2); // e.g., 14.50
            $table->timestamps();
        });
    }

 
    public function down(): void
    {
        Schema::dropIfExists('grades');
    }
};
