<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('yearly_reports', function (Blueprint $table) {
            $table->id();
            $table->integer('from')->nullable();
            $table->integer('to')->nullable();
            $table->string('name')->nullable(); // စီမံကိန်းအမည်
            $table->string('location')->nullable(); // လုပ်ငန်းတည်နေရာ
            $table->string('start_month')->nullable(); // စီမံကိန်းစတင်မည့်ကာလ
            $table->string('end_month')->nullable(); // စီမံကိန်းပြီးစီးမည့်ကာလ
            $table->string('department')->nullable(); // ဆောင်ရွက်မည့်ဌာန/အဖွဲ့အစည်း
            $table->year('operation_year')->nullable(); // လုပ်ငန်းဆောင်ရွက်သည့်နှစ်
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('yearly_reports');
    }
};
