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
        Schema::create('fixed_asset_transfer_with_spec_delete_histories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('old_id');
            $table->date('date')->nullable();
            $table->unsignedBigInteger('transfer_id');
            $table->string('reference');
            $table->unsignedBigInteger('asset_id');
            $table->unsignedBigInteger('spec_id');
            $table->string('rate')->nullable();
            $table->string('qty')->nullable();
            $table->text('purpose')->nullable();
            $table->text('remarks')->nullable();
            $table->dateTime('old_created_at')->nullable();
            $table->dateTime('old_updated_at')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();
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
        Schema::dropIfExists('fixed_asset_transfer_with_spec_delete_histories');
    }
};
