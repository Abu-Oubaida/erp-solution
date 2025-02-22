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
        Schema::create('document_requisition_infos', function (Blueprint $table) {
            $table->id();
            $table->string('reference_number')->unique();
            $table->string('status')->comment('0=inactive,1=active,100=solved');
            $table->unsignedBigInteger('sander_company_id');
            $table->unsignedBigInteger('receiver_company_id');
            $table->dateTime('deadline')->nullable();
            $table->integer('number_of_document')->nullable();
            $table->string('subject')->nullable();
            $table->text('description')->nullable();
            $table->unsignedBigInteger("created_by")->nullable();
            $table->unsignedBigInteger("updated_by")->nullable();
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
        Schema::dropIfExists('document_requisition_infos');
    }
};
