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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('image');
            $table->unsignedBigInteger('position_id')->nullable();
            $table->foreign('position_id')
              ->references('position_id')
              ->on('positions')
              ->onDelete('set null');
            $table->string('eid')->unique();
            $table->string('name');
            $table->string('email')->unique();
            $table->date('dob');
            $table->string('currentaddress');
            $table->string('phno');
            $table->boolean('super_user')->default(false);
            $table->string('department');
            $table->boolean('married_status')->default(false);
            $table->tinyInteger('gender');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
