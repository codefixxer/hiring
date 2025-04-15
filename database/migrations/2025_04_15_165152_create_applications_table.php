<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateApplicationsTable extends Migration
{
    public function up()
    {
        Schema::create('applications', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('job_id');
            $table->unsignedBigInteger('employee_id');
            $table->string('cv_file')->nullable();         // file path for uploaded CV
            $table->string('cv_link')->nullable();         // alternative URL for CV
            $table->text('cover_letter');                   // cover letter text
            $table->text('selected_skills')->nullable();    // comma separated string of skills
            $table->decimal('expected_salary', 8, 2)->nullable();
            $table->date('available_start_date')->nullable();
            $table->string('remarks')->nullable();
            $table->enum('status', ['applied','reviewed','accepted','rejected'])->default('applied');
            $table->timestamps();

            $table->foreign('job_id')->references('id')->on('jobs')->onDelete('cascade');
            $table->foreign('employee_id')->references('id')->on('users')->onDelete('cascade');
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('applications');
    }
}
