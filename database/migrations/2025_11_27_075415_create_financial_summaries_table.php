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
        Schema::create('financial_summaries', function (Blueprint $table) {
          $table->bigIncrements('id');
            $table->unsignedBigInteger('member_id');
            $table->integer('year');
            $table->integer('month_num');
            $table->string('month_name', 20);
            
            // Member-specific amounts for this month
            $table->integer('disbursement_amount')->default(0);  // Disbursement made to this member in this month
            $table->integer('collection_amount')->default(0);   // Collections received from this member in this month
            $table->integer('demand_amount')->default(0);       // Demand for this member in this month
            $table->integer('outstanding_amount')->default(0); // Outstanding balance for this member at end of month
            
            $table->timestamps();
        
            $table->foreign('member_id')->references('id')->on('members')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('financial_summaries');
    }
};
