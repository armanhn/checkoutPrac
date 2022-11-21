<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('enrollments', function (Blueprint $table) {
            $table->id();
            $table->string('student_name');
            $table->string('student_email');
            $table->string('school_attended')->nullable();
            $table->string('gender');
            $table->string('dob');
            $table->string('student_cell');
            $table->string('home_phone');
            $table->string('address');
            $table->string('parent_name')->nullable();
            $table->string('parent_email')->nullable();
            $table->string('parent_cell')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('enrollments');
    }
};
