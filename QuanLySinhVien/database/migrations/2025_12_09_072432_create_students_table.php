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
            $table->char('student_id', 12)->primary();
            $table->string('full_name', 100);
            $table->date('birth_date');
            $table->string('avatar', 255)->nullable();
            $table->char('class_id', 15);
            $table->timestamps();

            $table->foreign('class_id')
                  ->references('class_id')
                  ->on('classes')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');
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
