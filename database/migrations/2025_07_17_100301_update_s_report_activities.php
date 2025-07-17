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
        Schema::table('t_student_report_activities', function (Blueprint $table) {
            // update ACTIVITY_NAME lenght
            $table->string('ACTIVITY_NAME', 225)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('t_student_report_activities', function (Blueprint $table) {
            $table->string('ACTIVITY_NAME', 80)->change();
        });
    }
};
