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
        Schema::create('sales_lead_source_infos', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->unsignedBigInteger('company_id');
            $table->integer('status')->default('1')->comment('1=active other wise inactive');
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->integer('is_parent')->nullable()->comment('1=parent,otherwise child');
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
        Schema::dropIfExists('sales_lead_source_infos');
    }
};
