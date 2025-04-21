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
        Schema::create('data_archive_storage_package_delete_histories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('old_id');
            $table->integer('status')->default(1)->comment('1=active, 0=inactive');
            $table->string('package_name');
            $table->string('package_size');
            $table->string('package_type')->nullable();
            $table->unsignedBigInteger('old_created_by')->nullable();
            $table->timestamp('old_created_at')->nullable();
            $table->unsignedBigInteger('old_updated_by')->nullable();
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
        Schema::dropIfExists('data_archive_storage_package_delete_histories');
    }
};
