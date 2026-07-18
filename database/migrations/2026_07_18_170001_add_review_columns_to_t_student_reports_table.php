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
        Schema::table('t_student_reports', function (Blueprint $table) {
            $table->integer('review_star')->nullable()->default(null)->after('SR_IS_READ');
            $table->text('review')->nullable()->default(null)->after('review_star');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('t_student_reports', function (Blueprint $table) {
            $table->dropColumn('review_star');
            $table->dropColumn('review');
        });
    }
};
