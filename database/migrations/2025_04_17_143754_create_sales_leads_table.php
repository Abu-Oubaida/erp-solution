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
            $table->integer('status')->default('1')->comment('1= active otherwise inactive');
            $table->string('full_name');
            $table->string('spouse')->nullable();
            $table->string('primary_mobile');
            $table->string('primary_email')->nullable();
            $table->unsignedBigInteger('lead_main_profession_id')->nullable();
            $table->unsignedBigInteger('lead_sub_profession_id')->nullable();
            $table->string('lead_company')->nullable();
            $table->string('lead_designation')->nullable();
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
