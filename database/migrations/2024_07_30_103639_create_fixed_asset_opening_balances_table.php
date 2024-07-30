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
        Schema::create('fixed_asset_opening_balances', function (Blueprint $table) {
            $table->id();
            $table->text('date')->nullable();
            $table->string('references');
            $table->unsignedBigInteger('branch_id')->comment('project');
            $table->unsignedBigInteger('company_id');
            $table->integer('status')->default(0)->comment('0-inactive,1-active,2-approved,3-pending, 4-declined,5-processing');
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->text('narration')->nullable();
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
        Schema::dropIfExists('fixed_asset_opening_balances');
    }
};
