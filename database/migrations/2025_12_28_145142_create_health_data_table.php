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
        Schema::create('health_data', function (Blueprint $table) {
            $table->id();
            $table->string('patient_id')->index();
            $table->string('patient_name');
            $table->integer('age')->nullable();
            $table->enum('gender', ['L', 'P'])->nullable();
            $table->string('diagnosis')->nullable();
            $table->string('treatment')->nullable();
            $table->decimal('blood_pressure_systolic', 5, 2)->nullable();
            $table->decimal('blood_pressure_diastolic', 5, 2)->nullable();
            $table->decimal('heart_rate', 5, 2)->nullable();
            $table->decimal('temperature', 4, 2)->nullable();
            $table->text('notes')->nullable();
            $table->date('visit_date');
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->enum('data_status', ['valid', 'invalid', 'pending'])->default('pending');
            $table->decimal('quality_score', 5, 2)->nullable();
            $table->boolean('is_complete')->default(false);
            $table->boolean('is_accurate')->default(false);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('health_data');
    }
};
