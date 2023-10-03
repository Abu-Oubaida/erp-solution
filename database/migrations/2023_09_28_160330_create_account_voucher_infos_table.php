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
        Schema::create('account_voucher_infos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('voucher_type_id');
            $table->foreign('voucher_type_id')->references('id')->on('voucher_types')->onDelete('cascade');
            $table->date('voucher_date');
            $table->string('voucher_number');
            $table->integer('file_count')->nullable()->comment('if multiple file in a request');
            $table->text('remarks')->nullable();
            $table->unsignedBigInteger('created_by');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('cascade');
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
        Schema::dropIfExists('account_voucher_infos');
    }
};
