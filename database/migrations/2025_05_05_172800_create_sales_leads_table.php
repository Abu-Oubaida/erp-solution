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
        Schema::create('sales_leads', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id');
            $table->integer('status')->default('5')->comment('5 = pending');
            $table->string('full_name');
            $table->string('spouse')->nullable();
            $table->string('primary_mobile');
            $table->string('primary_email')->nullable();
            $table->text('notes')->nullable();
            $table->unsignedBigInteger('lead_status_id')->nullable();
            $table->integer('sell_status')->default(0)->comment("1=sold, other wise unsold")->nullable();
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
        Schema::dropIfExists('sales_leads');
    }
};
