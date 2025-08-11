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
        Schema::create('employee_housings', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->unsignedBigInteger('user_id'); // Foreign key to employees
            $table->string('family_member');
            $table->string('house_hold_img')->nullable();
            $table->string('township')->required();
            $table->text('description'); // Family member details
            $table->string('status'); // Request status (e.g., pending, approved)
            $table->date('submit_date'); // Date of submission
            $table->date('approved_date')->nullable(); // Date of approval, nullable if not approved yet
            // Foreign key constraint linking to employees table
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_housings');
    }
};
