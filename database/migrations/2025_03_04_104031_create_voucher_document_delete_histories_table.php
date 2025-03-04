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
        Schema::create('voucher_document_delete_histories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('old_id')->nullable();
            $table->unsignedBigInteger('company_id')->nullable();
            $table->unsignedBigInteger('voucher_type_id')->nullable();
            $table->date('voucher_date')->nullable();
            $table->string('voucher_number')->nullable();
            $table->integer('file_count')->nullable()->comment('if multiple file in a request');
            $table->text('remarks')->nullable();
            $table->unsignedBigInteger('old_created_by')->nullable();
            $table->unsignedBigInteger('old_updated_by')->nullable();
            $table->timestamp('old_created_at')->nullable();
            $table->timestamp('old_updated_at')->nullable();
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
        Schema::dropIfExists('voucher_document_delete_histories');
    }
};
