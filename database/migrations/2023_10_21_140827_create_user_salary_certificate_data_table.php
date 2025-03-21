<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_salary_certificate_data', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id');
            $table->integer('status')->default(1)->comment('1=active');
            $table->unsignedBigInteger('user_id');
            $table->string('financial_yer_from')->nullable();
            $table->string('financial_yer_to')->nullable();
            $table->string('basic')->nullable();
            $table->string('house_rent')->nullable();
            $table->string('conveyance')->nullable();
            $table->string('medical_allowance')->nullable();
            $table->string('festival_bonus')->nullable();
            $table->string('others')->nullable();
            $table->text('remarks')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_salary_certificate_data');
    }
};
