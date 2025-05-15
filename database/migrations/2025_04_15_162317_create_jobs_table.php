<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJobsTable extends Migration
{
    public function up()
    {
        Schema::create('jobs', function (Blueprint $table) {
            $table->id();

            // Foreign key to employer
            $table->unsignedBigInteger('employer_id');

            // Store multiple agent IDs as JSON
            $table->json('agent_ids')->nullable();

            // Job fields
            $table->string('job_title');
            $table->text('job_description');
            $table->string('skills')->nullable(); // comma-separated string like "php,laravel,vue"
            $table->enum('preferred_gender', ['any', 'male', 'female', 'other'])->default('any');
            $table->integer('minimum_experience')->default(0);
            $table->string('education_level')->nullable();
            $table->string('languages')->nullable();
            $table->string('certifications')->nullable();
            $table->decimal('min_salary', 8, 2);
            $table->decimal('max_salary', 8, 2);
            $table->integer('working_hours')->default(40);
            $table->date('posting_date');
            $table->date('closing_date');
            $table->enum('status', ['active', 'draft', 'closed'])->default('draft');

            $table->timestamps();

            // Foreign key constraint
            $table->foreign('employer_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('jobs');
    }
}
