<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('medical_records', function (Blueprint $table) {
            // Family history
            $table->boolean('family_hypertension')->default(false);
            $table->boolean('family_diabetes')->default(false);
            $table->boolean('family_heart_disease')->default(false);
            $table->boolean('family_cancer')->default(false);
            $table->boolean('family_tb')->default(false);

            // Immunizations
            $table->text('childhood_vaccines')->nullable();
            $table->text('adult_vaccines')->nullable();

            // Past surgeries
            $table->text('past_surgeries')->nullable();

            // Pre-existing conditions
            $table->text('pre_conditions')->nullable();

            // Medication compliance
            $table->text('medication_compliance')->nullable();

            // Treatment history
            $table->text('treatment_history')->nullable();

            // Lab results
            $table->text('lab_results')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('medical_records', function (Blueprint $table) {
            $table->dropColumn([
                'family_hypertension',
                'family_diabetes',
                'family_heart_disease',
                'family_cancer',
                'family_tb',
                'childhood_vaccines',
                'adult_vaccines',
                'past_surgeries',
                'pre_conditions',
                'medication_compliance',
                'treatment_history',
                'lab_results',
            ]);
        });
    }
};
