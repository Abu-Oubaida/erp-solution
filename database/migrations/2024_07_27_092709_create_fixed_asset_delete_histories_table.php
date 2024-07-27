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
        Schema::create('fixed_asset_delete_histories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('old_asset_id');
            $table->string('recourse_code');
            $table->string('materials_name');
            $table->string('rate');
            $table->string('unit');
            $table->string('depreciation')->nullable();
            $table->integer('status')->default(1)->comment('1=active, 0=inactive, 3=deleted');
            $table->text('remarks')->nullable();
            $table->unsignedBigInteger('company_id')->nullable();
            $table->unsignedBigInteger('created_by');
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->string('old_created_time')->nullable();
            $table->string('old_updated_time')->nullable();
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
        Schema::dropIfExists('fixed_asset_delete_histories');
    }
};
