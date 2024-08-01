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
        Schema::create('fixed_asset_opening_with_specs', function (Blueprint $table) {
            $table->id();
            $table->date('date')->nullable();
            $table->unsignedBigInteger('opening_asset_id');
            $table->string('references');
            $table->unsignedBigInteger('company_id');
            $table->unsignedBigInteger('asset_id');
            $table->unsignedBigInteger('spec_id');
            $table->string('rate')->nullable();
            $table->string('qty')->nullable();
            $table->text('purpose')->nullable();
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
        Schema::dropIfExists('fixed_asset_opening_with_specs');
    }
};
