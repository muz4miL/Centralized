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
        Schema::create('fee_summaries', function (Blueprint $table) {
            $table->id();
            $table->string('campus', 100);
            $table->date('month');                            // first day of the month
            $table->decimal('total_expected', 12, 2);         // total fees expected
            $table->decimal('total_collected', 12, 2);        // total fees collected
            $table->decimal('total_outstanding', 12, 2);      // expected - collected
            $table->decimal('collection_percentage', 5, 2);   // e.g. 87.50
            $table->unsignedInteger('students_paid');
            $table->unsignedInteger('students_unpaid');
            $table->timestamps();

            $table->index(['campus', 'month']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fee_summaries');
    }
};
