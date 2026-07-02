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
        Schema::create('exam_summaries', function (Blueprint $table) {
            $table->id();
            $table->string('campus', 100);
            $table->string('class_name', 50);
            $table->string('exam_name', 100);               // e.g. "Mid-Term 2024", "Final 2024"
            $table->date('exam_date');
            $table->unsignedInteger('total_students');
            $table->unsignedInteger('students_passed');
            $table->unsignedInteger('students_failed');
            $table->decimal('pass_percentage', 5, 2);        // e.g. 78.50
            $table->decimal('average_score', 5, 2);          // e.g. 72.30
            $table->decimal('highest_score', 5, 2)->nullable();
            $table->decimal('lowest_score', 5, 2)->nullable();
            $table->timestamps();

            $table->index(['campus', 'exam_date']);
            $table->index(['class_name', 'exam_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exam_summaries');
    }
};
