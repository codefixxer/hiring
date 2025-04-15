<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJobSkillsTable extends Migration
{
    public function up()
    {
        Schema::create('job_skills', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('job_id');
            $table->string('skill_name');
            $table->timestamps();
            $table->foreign('job_id')->references('id')->on('jobs')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('job_skills');
    }
}
