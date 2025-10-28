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
        //
        Schema::table('users', function (Blueprint $table) {
            //
            $table->integer('age');
            $table->float('percentage');
            $table->string('profileImage')->nullable(true);
            $table->date('date_of_birth')->nullable();
            $table->enum('gender', ['male', 'female'])->default('male');
            $table->enum('userType', ['student', 'teacher'])->default('student');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        Schema::table('users', function (Blueprint $table) {
            //
            $table->dropColumn('age');
            $table->dropColumn('percentage');
            $table->dropColumn('profileImage');
            $table->dropColumn('date_of_birth');
            $table->dropColumn('gender');
            $table->dropColumn('userType');
        });
    }
};
