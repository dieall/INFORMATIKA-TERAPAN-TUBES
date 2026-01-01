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
        Schema::create('data_quality_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('health_data_id')->nullable()->constrained('health_data')->onDelete('cascade');
            $table->string('check_type'); // accuracy, completeness, consistency, validity
            $table->enum('status', ['passed', 'failed', 'warning'])->default('passed');
            $table->text('message')->nullable();
            $table->json('details')->nullable();
            $table->decimal('score', 5, 2)->nullable();
            $table->foreignId('checked_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('data_quality_logs');
    }
};
