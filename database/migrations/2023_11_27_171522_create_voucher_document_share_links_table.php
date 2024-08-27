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
        Schema::create('voucher_document_share_links', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id');
            $table->string('share_id')->nullable();
            $table->integer('share_type')->comment('1=only view, 2=view and download')->default(1);
            $table->unsignedBigInteger('share_document_id')->nullable();
            $table->integer('status')->comment('1=active, 2=inactive/delete')->nullable();
            $table->unsignedBigInteger('shared_by')->nullable();
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
        Schema::dropIfExists('voucher_document_share_links');
    }
};
