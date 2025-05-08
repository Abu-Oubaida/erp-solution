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
        Schema::create('project_wise_data_type_required_infos_delete_histories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('old_id');
            $table->unsignedBigInteger('company_id')->nullable();
            $table->unsignedBigInteger('pdri_id')->comment('Project Document Requisition Id');
            $table->unsignedBigInteger('data_type_id')->nullable();
            $table->timestamp('deadline')->nullable();
            $table->integer('status')->default(1)->comment('0-Optional, 1-Required');
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
        Schema::dropIfExists('project_wise_data_type_required_infos_delete_histories');
    }
};
