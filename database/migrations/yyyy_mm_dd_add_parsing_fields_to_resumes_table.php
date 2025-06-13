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
        Schema::table('resumes', function (Blueprint $table) {
            if (!Schema::hasColumn('resumes', 'parsing_status')) {
                $table->string('parsing_status')->default('pending')->after('cv_file_id')->comment('pending, processing, completed, error');
            }
            
            if (!Schema::hasColumn('resumes', 'parsing_error')) {
                $table->text('parsing_error')->nullable()->after('parsing_status');
            }
            
            if (!Schema::hasColumn('resumes', 'parsed_data')) {
                $table->json('parsed_data')->nullable()->after('parsing_error')->comment('Raw parsed data from n8n');
            }
            
            if (!Schema::hasColumn('resumes', 'parsed_skills')) {
                $table->text('parsed_skills')->nullable()->after('parsed_data');
            }
            
            if (!Schema::hasColumn('resumes', 'parsed_experience')) {
                $table->text('parsed_experience')->nullable()->after('parsed_skills');
            }
            
            if (!Schema::hasColumn('resumes', 'parsed_education')) {
                $table->text('parsed_education')->nullable()->after('parsed_experience');
            }
            
            if (!Schema::hasColumn('resumes', 'parsed_summary')) {
                $table->text('parsed_summary')->nullable()->after('parsed_education');
            }
            
            if (!Schema::hasColumn('resumes', 'total_years_experience')) {
                $table->float('total_years_experience')->nullable()->after('parsed_summary');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('resumes', function (Blueprint $table) {
            $table->dropColumn([
                'parsing_status',
                'parsing_error',
                'parsed_data',
                'parsed_skills',
                'parsed_experience',
                'parsed_education',
                'parsed_summary',
                'total_years_experience'
            ]);
        });
    }
}; 