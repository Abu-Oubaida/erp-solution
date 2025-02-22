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
        Schema::create('document_requisition_attested_document_commons', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('document_requisition_id');
            $table->text('document_name');
            $table->text('document_path')->nullable();
            $table->string('document_remarks')->nullable();
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
        Schema::dropIfExists('document_requisition_attested_document_commons');
    }
};
