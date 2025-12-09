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
        Schema::create('scores', function (Blueprint $table) {
            $table->id();
            $table->char('student_id', 12);
            $table->char('subject_id', 10);
            $table->decimal('cc', 4, 2)->nullable();
            $table->decimal('gk', 4, 2)->nullable();
            $table->decimal('ck', 4, 2)->nullable();
            $table->decimal('total', 4, 2)->nullable();
            $table->timestamps();

            $table->foreign('student_id')
                  ->references('student_id')
                  ->on('students')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');

            $table->foreign('subject_id')
                  ->references('subject_id')
                  ->on('subjects')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('scores');
    }
};
