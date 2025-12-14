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
        Schema::table('classes', function (Blueprint $table) {
            // Xóa cột advisor cũ
            $table->dropColumn('advisor');
            
            // Thêm cột teacher_id với foreign key
            $table->unsignedBigInteger('teacher_id')->nullable()->after('department');
            $table->foreign('teacher_id')
                  ->references('id')
                  ->on('teachers')
                  ->onDelete('set null')
                  ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('classes', function (Blueprint $table) {
            // Xóa foreign key và cột teacher_id
            $table->dropForeign(['teacher_id']);
            $table->dropColumn('teacher_id');
            
            // Thêm lại cột advisor
            $table->string('advisor', 100)->after('department');
        });
    }
};
