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
        Schema::create('fixed_asset_transfers', function (Blueprint $table) {
            $table->id();
            $table->string('status')->default('0')->comment('0=running, 1=stage 1, 2=stage 2, 3=stage 3, 4=stage 4, 5=stage 6, 9999=finale stage');
            $table->date('date')->nullable();
            $table->string('reference');
            $table->unsignedBigInteger('from_company_id')->nullable();
            $table->unsignedBigInteger('from_project_id')->nullable();
            $table->unsignedBigInteger('to_company_id')->nullable();
            $table->unsignedBigInteger('to_project_id')->nullable();
            $table->text('narration')->nullable();
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
        Schema::dropIfExists('fixed_asset_transfers');
    }
};
