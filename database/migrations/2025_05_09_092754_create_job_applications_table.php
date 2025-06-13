<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('job_applications', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('job_id');
            $table->unsignedBigInteger('resume_id');
            $table->string('status')->default('pending');
            $table->integer('score')->default(0);
            $table->text('score_reason')->nullable();
            $table->integer('position_match')->default(0);
            $table->integer('experience_match')->default(0);
            $table->integer('skills_match')->default(0);
            $table->integer('education_match')->default(0);
            $table->integer('location_match')->default(0);
            $table->integer('industry_fit')->default(0);
            $table->timestamps();
            
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('job_id')->references('id')->on('jobs');
            $table->foreign('resume_id')->references('id')->on('resumes');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('job_applications');
    }
};