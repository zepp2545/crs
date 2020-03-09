<?php

use Illuminate\Support\Facades\Schema;
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
        Schema::create('students', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('grade');
            $table->string('jaName');
            $table->string('kanaName');
            $table->string('enName');
            $table->string('tel1');
            $table->string('tel2')->nullable();
            $table->string('email1');
            $table->string('email2')->nullable();
            $table->integer('address_id');
            $table->string('addDetails')->nullable();
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
        Schema::dropIfExists('students');
    }
}
