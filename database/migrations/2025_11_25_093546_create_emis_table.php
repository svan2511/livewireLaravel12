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
        Schema::create('emis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('member_id');
            $table->string('inst_name')->nullable();
            $table->integer('inst_amount')->nullable();
            $table->integer('paid_amount')->nullable();
            $table->integer('remain_amount')->nullable();
            $table->tinyInteger('status')->default(0);
            $table->date('paid_on')->nullable();
            $table->date('due_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('emis');
    }
};
