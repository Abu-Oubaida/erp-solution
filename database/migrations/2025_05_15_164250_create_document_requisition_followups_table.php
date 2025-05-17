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
        Schema::create('document_requisition_followups', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id');
            $table->text('subject')->nullable();
            $table->text('description')->nullable();
            $table->boolean('notification')->nullable();
            $table->boolean('email')->nullable();
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
        Schema::dropIfExists('document_requisition_followups');
    }
};
