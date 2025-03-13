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
        Schema::create('archive_link_document_delete_histories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('old_id');
            $table->unsignedBigInteger('voucher_info_id')->nullable();
            $table->unsignedBigInteger('company_id')->nullable();
            $table->unsignedBigInteger('document_id')->nullable();
            $table->timestamp('old_created_at')->nullable();
            $table->timestamp('old_updated_at')->nullable();
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
        Schema::dropIfExists('archive_link_document_delete_histories');
    }
};
