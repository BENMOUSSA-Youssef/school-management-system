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
        Schema::create('students', function (Blueprint $table) {
            $table->id(); // The unique ID for the student
            $table->string('registration_number'); // Student ID number (e.g., EMSI-2024)
            $table->string('full_name');           // Student Name
            $table->string('class');               // e.g., "Classe A"
            $table->float('average_grade');        // Their score (e.g., 15.5)
            $table->timestamps(); // Created_at and Updated_at columns
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};