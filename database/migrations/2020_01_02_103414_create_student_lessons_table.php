<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStudentLessonsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('student_lessons', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('student_id');
            $table->integer('lesson_id');
            $table->date('trial_date')->nullable();
            $table->date('start_date')->nullable();
            $table->date('quit_date')->nullable();
            $table->integer('status');
            $table->integer('bus')->nullable();
            $table->integer('pickup_id')->nullable();
            $table->string('pickup_details')->nullable();
            $table->integer('send_id')->nullable();
            $table->string('send_details')->nullable();
            $table->string('note')->nullable();
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
        Schema::dropIfExists('student_lessons');
    }
}
