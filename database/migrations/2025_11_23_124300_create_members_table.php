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
        Schema::create('members', function (Blueprint $table) {
            $table->id();
            $table->string('mem_name');
            $table->string('mem_img');
            $table->foreignId('center_id')->nullable();
            $table->integer('disb_amount');
            $table->date('disb_date');
            $table->string('mem_tenor')->nullable();
            $table->string('monthly_inst')->nullable();
            $table->string('mem_phone')->nullable();
            $table->tinyInteger('status')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('members');
    }
};
