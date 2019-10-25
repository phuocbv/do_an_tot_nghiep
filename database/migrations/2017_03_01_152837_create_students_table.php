<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStudentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('students', function ($table) {
            $table->increments('id');
            $table->string('name')->nullable();
            $table->string('gender')->nullable();
            $table->string('birthday')->nullable();
            $table->string('grade')->nullable();
            $table->string('program_university')->nullable();
            $table->string('msv')->nullable();
            $table->string('phone')->nullable();
            $table->string('english')->nullable();
            $table->string('programing_skill')->nullable();
            $table->string('programing_skill_best')->nullable();
            $table->string('user_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('students');
    }
}
