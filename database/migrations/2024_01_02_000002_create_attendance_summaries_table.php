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
        Schema::create('attendance_summaries', function (Blueprint $table) {
            $table->id();
            $table->string('campus', 100);
            $table->string('class_name', 50);
            $table->date('month');                          // first day of the month
            $table->unsignedInteger('total_students');
            $table->unsignedInteger('total_present');
            $table->unsignedInteger('total_absent');
            $table->decimal('attendance_percentage', 5, 2); // e.g. 92.35
            $table->timestamps();

            $table->index(['campus', 'month']);
            $table->index(['class_name', 'month']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendance_summaries');
    }
};
